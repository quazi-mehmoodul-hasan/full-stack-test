<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/models/Tab.php';
require_once __DIR__ . '/../src/models/Slide.php';

require_login();

$id    = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$slide = $id ? Slide::find($id) : null;
if ($id && !$slide) {
    set_flash('error', 'Slide not found.');
    redirect('/admin/');
}

$tabs = Tab::all();
if (empty($tabs)) {
    set_flash('error', 'Create a tab before adding slides.');
    redirect('/admin/');
}

// Pre-selected tab: from the slide being edited, or ?tab_id= when adding.
$selectedTab = (int) ($slide['tab_id'] ?? ($_GET['tab_id'] ?? $tabs[0]['id']));

$pageTitle = $slide ? 'Edit slide' : 'Add slide';
require __DIR__ . '/../src/partials/header.php';
?>
<h1 class="h3 mb-4"><?= esc($pageTitle) ?></h1>

<form method="post" action="/admin/slide_save.php" enctype="multipart/form-data" class="card shadow-sm">
  <div class="card-body" style="max-width:720px">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) ($slide['id'] ?? 0) ?>">

    <div class="form-group">
      <label for="tab_id">Tab</label>
      <select class="form-control" id="tab_id" name="tab_id" required>
        <?php foreach ($tabs as $tab): ?>
          <option value="<?= (int) $tab['id'] ?>" <?= ($selectedTab === (int) $tab['id']) ? 'selected' : '' ?>>
            <?= esc($tab['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="category_label">Category label</label>
      <input type="text" class="form-control" id="category_label" name="category_label"
             minlength="2" maxlength="150" required
             value="<?= esc($slide['category_label'] ?? '') ?>"
             placeholder="DIGITAL LEARNING INFRASTRUCTURE">
    </div>

    <div class="form-group">
      <label for="title">Title</label>
      <textarea class="form-control" id="title" name="title" rows="2" minlength="2" maxlength="255" required><?= esc($slide['title'] ?? '') ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="link_text">Link text</label>
        <input type="text" class="form-control" id="link_text" name="link_text"
               maxlength="100"
               value="<?= esc($slide['link_text'] ?? 'Learn More') ?>">
      </div>
      <div class="form-group col-md-6">
        <label for="link_url">Link URL</label>
        <input type="text" class="form-control" id="link_url" name="link_url"
               maxlength="255"
               value="<?= esc($slide['link_url'] ?? '#') ?>">
      </div>
    </div>

    <div class="form-group">
      <label for="image">Image <?= $slide ? '(leave empty to keep current)' : '' ?></label>
      <?php if (!empty($slide['image_path'])): ?>
        <div class="mb-2">
          <img src="<?= esc(public_url($slide['image_path'])) ?>" alt=""
               style="width:120px;height:120px;object-fit:cover;border-radius:6px;">
        </div>
      <?php endif; ?>
      <input type="file" class="form-control-file" id="image" name="image" accept="image/*" <?= $slide ? '' : 'required' ?>>
      <small class="text-muted">1:1 image recommended. JPG/PNG/GIF/WEBP, max 5&nbsp;MB.</small>
    </div>

    <div class="form-group">
      <label for="sort_order">Sort order</label>
      <input type="number" class="form-control" id="sort_order" name="sort_order"
             value="<?= (int) ($slide['sort_order'] ?? 0) ?>" style="max-width:140px">
    </div>

    <button type="submit" class="btn btn-brand">Save</button>
    <a href="/admin/" class="btn btn-link">Cancel</a>
  </div>
</form>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
