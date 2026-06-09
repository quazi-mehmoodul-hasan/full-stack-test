<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/models/Tab.php';
require_once __DIR__ . '/../src/models/Slide.php';

require_login();

$tabs = Tab::all();

$pageTitle = 'Dashboard';
require __DIR__ . '/../src/partials/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Tabs &amp; Slides</h1>
  <a href="/admin/tab_form.php" class="btn btn-brand">+ Add tab</a>
</div>

<?php if (empty($tabs)): ?>
  <p class="text-muted">No tabs yet. Create your first one.</p>
<?php else: ?>
  <?php foreach ($tabs as $tab): ?>
    <?php $slides = Slide::forTab((int) $tab['id']); ?>
    <div class="card mb-4 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <strong><?= esc($tab['name']) ?></strong>
          <span class="text-muted small ml-2">order: <?= (int) $tab['sort_order'] ?> · icon: <?= esc($tab['icon'] ?: '—') ?></span>
        </div>
        <div>
          <a href="/admin/slide_form.php?tab_id=<?= (int) $tab['id'] ?>" class="btn btn-sm btn-outline-primary">+ Add slide</a>
          <a href="/admin/tab_form.php?id=<?= (int) $tab['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
          <form method="post" action="/admin/tab_delete.php" class="d-inline"
                onsubmit="return confirm('Delete this tab and all its slides?');">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $tab['id'] ?>">
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </div>
      </div>

      <div class="card-body p-0">
        <?php if (empty($slides)): ?>
          <p class="text-muted p-3 mb-0">No slides yet.</p>
        <?php else: ?>
          <table class="table table-sm table-hover mb-0">
            <thead>
              <tr>
                <th style="width:70px">Image</th>
                <th>Title</th>
                <th>Label</th>
                <th style="width:70px">Order</th>
                <th style="width:150px"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($slides as $slide): ?>
                <tr>
                  <td>
                    <?php if ($slide['image_path']): ?>
                      <img src="<?= esc(public_url($slide['image_path'])) ?>" alt=""
                           style="width:54px;height:54px;object-fit:cover;border-radius:4px;">
                    <?php else: ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>
                  <td><?= esc($slide['title']) ?></td>
                  <td class="small text-muted"><?= esc($slide['category_label']) ?></td>
                  <td><?= (int) $slide['sort_order'] ?></td>
                  <td>
                    <a href="/admin/slide_form.php?id=<?= (int) $slide['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="post" action="/admin/slide_delete.php" class="d-inline"
                          onsubmit="return confirm('Delete this slide?');">
                      <?= csrf_field() ?>
                      <input type="hidden" name="id" value="<?= (int) $slide['id'] ?>">
                      <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
