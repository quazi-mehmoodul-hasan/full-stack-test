<?php
/**
 * Minimal shared-password admin gate (demo only — not real user accounts).
 *
 * Default credentials are admin / admin. They are intentionally shown on the
 * login page and documented in the README. Override via the ADMIN_USER /
 * ADMIN_PASS environment variables.
 */
declare(strict_types=1);

const ADMIN_DEFAULT_USER = 'admin';
const ADMIN_DEFAULT_PASS = 'admin';

function admin_user(): string
{
    return getenv('ADMIN_USER') ?: ADMIN_DEFAULT_USER;
}

function admin_pass(): string
{
    return getenv('ADMIN_PASS') ?: ADMIN_DEFAULT_PASS;
}

function start_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function is_logged_in(): bool
{
    start_session();
    return !empty($_SESSION['admin_authenticated']);
}

/** Validate a username/password pair against the configured credentials. */
function attempt_login(string $user, string $pass): bool
{
    $ok = hash_equals(admin_user(), $user) && hash_equals(admin_pass(), $pass);
    if ($ok) {
        start_session();
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
    }
    return $ok;
}

function logout(): void
{
    start_session();
    $_SESSION = [];
    session_destroy();
}

/** Guard an admin page; redirects to login when not authenticated. */
function require_login(): void
{
    if (!is_logged_in()) {
        redirect('/admin/login.php');
    }
}
