<?php
/** Admin chrome — top of every authenticated admin page. */
declare(strict_types=1);

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../auth.php';

$pageTitle = $pageTitle ?? 'Admin';
$flash = take_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($pageTitle) ?> · DelphianLogic Admin</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: 'Open Sans', sans-serif; background: #f6f6f6; }
    .navbar-brand { font-weight: 700; }
    .admin-navbar { background: #11324d; }
    .admin-navbar .navbar-brand,
    .admin-navbar .nav-link { color: #fff; }
    .table thead th { background: #11324d; color: #fff; border-color: #11324d; }
    .btn-brand { background: #c4351e; border-color: #c4351e; color: #fff; }
    .btn-brand:hover { background: #a82d19; border-color: #a82d19; color: #fff; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand admin-navbar mb-4">
  <div class="container">
    <a class="navbar-brand" href="/admin/">DelphianLogic Admin</a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="/public/" target="_blank">View site ↗</a></li>
      <li class="nav-item"><a class="nav-link" href="/admin/logout.php">Logout</a></li>
    </ul>
  </div>
</nav>
<div class="container pb-5">
  <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?>">
      <?= esc($flash['message']) ?>
    </div>
  <?php endif; ?>
