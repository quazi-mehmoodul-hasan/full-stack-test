<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/auth.php';

if (is_logged_in()) {
    redirect('/admin/');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $user = (string) ($_POST['username'] ?? '');
    $pass = (string) ($_POST['password'] ?? '');
    if (attempt_login($user, $pass)) {
        redirect('/admin/');
    }
    $error = 'Incorrect username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login · DelphianLogic Admin</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: 'Open Sans', sans-serif; background: #11324d; }
    .login-card { max-width: 380px; margin: 8vh auto; }
    .btn-brand { background: #c4351e; border-color: #c4351e; color: #fff; }
    .btn-brand:hover { background: #a82d19; border-color: #a82d19; color: #fff; }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="card shadow">
      <div class="card-body p-4">
        <h1 class="h4 mb-3 text-center">DelphianLogic Admin</h1>

        <?php if ($error): ?>
          <div class="alert alert-danger py-2"><?= esc($error) ?></div>
        <?php endif; ?>

        <div class="alert alert-info py-2 small mb-3">
          <strong>Demo credentials</strong><br>
          Username: <code><?= esc(admin_user()) ?></code><br>
          Password: <code><?= esc(admin_pass()) ?></code>
        </div>

        <form method="post" action="/admin/login.php">
          <?= csrf_field() ?>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username"
                   value="<?= esc(admin_user()) ?>" autofocus required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-brand btn-block">Log in</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
