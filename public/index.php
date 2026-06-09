<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/models/Tab.php';
require_once __DIR__ . '/../src/models/Slide.php';

$tabs = Tab::all();

// Pre-load slides per tab so the view stays simple.
$slidesByTab = [];
foreach ($tabs as $tab) {
    $slidesByTab[$tab['id']] = Slide::forTab((int) $tab['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DelphianLogic in Action</title>

  <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,400i,600&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700&amp;display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
  <link rel="stylesheet" href="<?= esc(public_url('assets/css/style.css')) ?>">
</head>
<body>

<section class="dl-section">
  <div class="container">
    <header class="dl-head">
      <h2 class="dl-head__title">DelphianLogic in Action</h2>
      <p class="dl-head__subtitle">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo</p>
    </header>

    <?php if (empty($tabs)): ?>
      <p class="text-center">No content yet. Add tabs and slides in the
        <a href="/admin/" style="color:var(--brand-fourth)">admin area</a>.</p>
    <?php else: ?>
      <div class="dl-widget" style="--tab-count: <?= count($tabs) ?>">
        <?php foreach ($tabs as $tab): ?>
          <?php
            $tabId  = (int) $tab['id'];
            $slides = $slidesByTab[$tabId];
          ?>
          <div class="dl-item" data-tab="<?= $tabId ?>">

            <button type="button" class="dl-tab">
              <?php if ($tab['icon']): ?>
                <img class="dl-tab__icon" src="<?= esc(icon_url($tab['icon'])) ?>" alt="">
              <?php endif; ?>
              <span class="dl-tab__name"><?= esc($tab['name']) ?></span>
              <span class="dl-tab__toggle">
                <img class="icon-plus" src="<?= esc(icon_url('plus-01.svg')) ?>" alt="Expand">
                <img class="icon-minus" src="<?= esc(icon_url('minus-01.svg')) ?>" alt="Collapse">
              </span>
            </button>

            <div class="dl-panel">
              <!-- Column 2: content slider (controls live here) -->
              <div class="dl-content" id="dl-content-<?= $tabId ?>">
                <?php foreach ($slides as $slide): ?>
                  <div class="dl-slide">
                    <div class="dl-slide__bg" style="background-image:url('<?= esc(public_url($slide['image_path'])) ?>')"></div>
                    <div class="dl-slide__inner">
                      <?php if ($slide['category_label']): ?>
                        <span class="dl-pill"><?= esc($slide['category_label']) ?></span>
                      <?php endif; ?>
                      <h3 class="dl-title"><?= esc($slide['title']) ?></h3>
                      <a class="dl-more" href="<?= esc($slide['link_url']) ?>">
                        <?= esc($slide['link_text']) ?>
                        <img class="dl-more__arrow" src="<?= esc(icon_url('arrow-right.svg')) ?>" alt="">
                      </a>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <!-- Column 3: 1:1 image slider, synced to Column 2 -->
              <div class="dl-images" id="dl-images-<?= $tabId ?>">
                <?php foreach ($slides as $slide): ?>
                  <div class="dl-image">
                    <img src="<?= esc(public_url($slide['image_path'])) ?>" alt="<?= esc($slide['title']) ?>">
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="<?= esc(public_url('assets/js/app.js')) ?>"></script>
</body>
</html>
