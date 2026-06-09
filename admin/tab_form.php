<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/models/Tab.php';

require_login();

$id  = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$tab = $id ? Tab::find($id) : null;
if ($id && !$tab) {
    set_flash('error', 'Tab not found.');
    redirect('/admin/');
}

// Icons available in public/assets/icons (category icons only).
$iconDir = dirname(__DIR__) . '/public/assets/icons';
$icons = array_values(array_filter(
    scandir($iconDir) ?: [],
    static fn ($f) => str_starts_with($f, 'DL-') && str_ends_with($f, '.svg')
));

$pageTitle = $tab ? 'Edit tab' : 'Add tab';
require __DIR__ . '/../src/partials/header.php';
?>
<h1 class="h3 mb-4"><?= esc($pageTitle) ?></h1>

<form method="post" action="/admin/tab_save.php" class="card shadow-sm">
  <div class="card-body" style="max-width:640px">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) ($tab['id'] ?? 0) ?>">

    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" id="name" name="name" required minlength="2" maxlength="100"
             value="<?= esc($tab['name'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="icon">Icon</label>
      <select class="form-control" id="icon" name="icon">
        <option value="">— none —</option>
        <?php foreach ($icons as $icon): ?>
          <option value="<?= esc($icon) ?>" <?= (($tab['icon'] ?? '') === $icon) ? 'selected' : '' ?>>
            <?= esc($icon) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="sort_order">Sort order</label>
      <input type="number" class="form-control" id="sort_order" name="sort_order"
             value="<?= (int) ($tab['sort_order'] ?? 0) ?>" style="max-width:140px">
    </div>

    <button type="submit" class="btn btn-brand">Save</button>
    <a href="/admin/" class="btn btn-link">Cancel</a>
  </div>
</form>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
