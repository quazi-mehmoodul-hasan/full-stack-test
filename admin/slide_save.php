<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/models/Tab.php';
require_once __DIR__ . '/../src/models/Slide.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/');
}
verify_csrf();

$id            = (int) ($_POST['id'] ?? 0);
$tabId         = (int) ($_POST['tab_id'] ?? 0);
$title         = trim((string) ($_POST['title'] ?? ''));
$categoryLabel = trim((string) ($_POST['category_label'] ?? ''));
$linkText      = trim((string) ($_POST['link_text'] ?? ''));
$linkUrl       = trim((string) ($_POST['link_url'] ?? ''));
$back          = $id ? "/admin/slide_form.php?id={$id}" : "/admin/slide_form.php?tab_id={$tabId}";

// Existing image is kept unless a new file is uploaded.
$existing = $id ? (Slide::find($id)['image_path'] ?? '') : '';

$errors = [];
if ($tabId <= 0 || Tab::find($tabId) === null) {
    $errors[] = 'Please choose a valid tab.';
}
if ($title === '') {
    $errors[] = 'Title is required.';
} elseif (mb_strlen($title) < 2) {
    $errors[] = 'Title must be at least 2 characters.';
} elseif (mb_strlen($title) > 255) {
    $errors[] = 'Title must be 255 characters or fewer.';
}
if ($categoryLabel === '') {
    $errors[] = 'Category label is required.';
} elseif (mb_strlen($categoryLabel) < 2) {
    $errors[] = 'Category label must be at least 2 characters.';
} elseif (mb_strlen($categoryLabel) > 150) {
    $errors[] = 'Category label must be 150 characters or fewer.';
}
if (mb_strlen($linkText) > 100) {
    $errors[] = 'Link text must be 100 characters or fewer.';
}
if (mb_strlen($linkUrl) > 255) {
    $errors[] = 'Link URL must be 255 characters or fewer.';
}

if ($errors) {
    set_flash('error', $errors[0]);
    redirect($back);
}

try {
    $uploaded = handle_image_upload($_FILES['image'] ?? []);
} catch (RuntimeException $e) {
    set_flash('error', $e->getMessage());
    redirect($back);
}

// A slide needs an image; require one when creating (none to fall back on).
$imagePath = $uploaded ?? $existing;
if ($imagePath === '') {
    set_flash('error', 'An image is required.');
    redirect($back);
}

$data = [
    'tab_id'         => $tabId,
    'category_label' => $categoryLabel,
    'title'          => $title,
    'link_text'      => $linkText !== '' ? $linkText : 'Learn More',
    'link_url'       => safe_url($linkUrl),
    'image_path'     => $imagePath,
    'sort_order'     => $_POST['sort_order'] ?? 0,
];

try {
    if ($id) {
        Slide::update($id, $data);
        // If the image was replaced, prune the previous file when unused.
        if ($uploaded !== null && $existing !== '' && $existing !== $uploaded) {
            Slide::pruneImage($existing, $id);
        }
        set_flash('success', 'Slide updated.');
    } else {
        Slide::create($data);
        set_flash('success', 'Slide created.');
    }
} catch (PDOException $e) {
    set_flash('error', 'Could not save the slide. Please try again.');
    redirect($back);
}

redirect('/admin/');
