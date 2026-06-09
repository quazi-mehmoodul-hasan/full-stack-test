<?php
/**
 * Small shared helpers: escaping, URLs, redirects, flash messages, image upload.
 */
declare(strict_types=1);

/** HTML-escape a value for safe output. */
function esc(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Absolute web URL for an asset stored under public/ (e.g. uploads/foo.jpg). */
function public_url(string $relativePath): string
{
    return '/public/' . ltrim($relativePath, '/');
}

/** Absolute web URL for an icon in public/assets/icons. */
function icon_url(string $filename): string
{
    return '/public/assets/icons/' . ltrim($filename, '/');
}

/**
 * Sanitise a user-supplied link URL. Allows http/https/mailto/tel, root-relative
 * paths and anchors; anything else (javascript:, data:, vbscript:, …) becomes '#'
 * so it can't execute when rendered in an href.
 */
function safe_url(string $url): string
{
    $url = trim($url);
    if ($url === '' || $url === '#') {
        return '#';
    }
    // relative path or anchor is fine
    if ($url[0] === '/' || $url[0] === '#') {
        return $url;
    }
    if (preg_match('#^(https?:)?//#i', $url)) {
        return $url; // http(s) or protocol-relative
    }
    if (preg_match('#^(mailto:|tel:)#i', $url)) {
        return $url;
    }
    // bare domain like "example.com/path" — treat as https
    if (preg_match('#^[a-z0-9.-]+\.[a-z]{2,}(/|$)#i', $url)) {
        return 'https://' . $url;
    }
    return '#';
}

/** Redirect and stop. */
function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

/** Store a one-shot flash message in the session. */
function set_flash(string $type, string $message): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/** Pop the flash message (returns null if none). */
function take_flash(): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/** Current CSRF token (created on first use). */
function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Hidden input carrying the CSRF token for forms. */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . esc(csrf_token()) . '">';
}

/** Abort with 400 if the submitted token does not match. */
function verify_csrf(): void
{
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(400);
        exit('Invalid CSRF token.');
    }
}

/**
 * Validate and store an uploaded image into public/uploads.
 * Returns the stored relative path (uploads/xxx.ext) or null if no file was sent.
 * Throws RuntimeException on a real validation/upload failure.
 */
function handle_image_upload(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null; // nothing uploaded — caller keeps the existing image
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed (error code ' . $file['error'] . ').');
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Image is too large (max 5 MB).');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Unsupported image type. Use JPG, PNG, GIF or WEBP.');
    }

    $uploadsDir = dirname(__DIR__) . '/public/uploads';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0775, true);
    }

    $name = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    $dest = $uploadsDir . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Could not save the uploaded image.');
    }

    return 'uploads/' . $name;
}
