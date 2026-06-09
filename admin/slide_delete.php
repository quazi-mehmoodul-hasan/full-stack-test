<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/models/Slide.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/');
}
verify_csrf();

$id = (int) ($_POST['id'] ?? 0);
if ($id) {
    Slide::delete($id);
    set_flash('success', 'Slide deleted.');
}

redirect('/admin/');
