<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/models/Tab.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/');
}
verify_csrf();

$id   = (int) ($_POST['id'] ?? 0);
$name = trim((string) ($_POST['name'] ?? ''));
$icon = trim((string) ($_POST['icon'] ?? ''));
$back = $id ? "/admin/tab_form.php?id={$id}" : '/admin/tab_form.php';

// Icons must come from the known set (or be empty); guards direct POSTs.
$allowedIcons = array_values(array_filter(
    scandir(dirname(__DIR__) . '/public/assets/icons') ?: [],
    static fn ($f) => str_starts_with($f, 'DL-') && str_ends_with($f, '.svg')
));

$errors = [];
if ($name === '') {
    $errors[] = 'Name is required.';
} elseif (mb_strlen($name) < 2) {
    $errors[] = 'Name must be at least 2 characters.';
} elseif (mb_strlen($name) > 100) {
    $errors[] = 'Name must be 100 characters or fewer.';
}
if ($icon !== '' && !in_array($icon, $allowedIcons, true)) {
    $errors[] = 'Please choose a valid icon.';
}

if ($errors) {
    set_flash('error', $errors[0]);
    redirect($back);
}

$data = ['name' => $name, 'icon' => $icon, 'sort_order' => $_POST['sort_order'] ?? 0];

try {
    if ($id) {
        Tab::update($id, $data);
        set_flash('success', 'Tab updated.');
    } else {
        Tab::create($data);
        set_flash('success', 'Tab created.');
    }
} catch (PDOException $e) {
    // 23000 = integrity constraint (e.g. duplicate slug from a duplicate name)
    $msg = ($e->getCode() === '23000')
        ? 'A tab with that name already exists.'
        : 'Could not save the tab. Please try again.';
    set_flash('error', $msg);
    redirect($back);
}

redirect('/admin/');
