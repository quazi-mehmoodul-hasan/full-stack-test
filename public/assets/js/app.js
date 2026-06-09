/* DelphianLogic in Action — tab switching + synced Slick sliders.
   Each tab owns one panel containing a content slider (Column 2) and an image
   slider (Column 3), kept in sync via Slick's asNavFor. */
(function ($) {
  'use strict';

  function initPanel($item) {
    var $content = $item.find('.dl-content');
    var $images = $item.find('.dl-images');
    if (!$content.length || $content.hasClass('slick-initialized')) {
      return;
    }

    var contentSel = '#' + $content.attr('id');
    var imagesSel = '#' + $images.attr('id');

    // Image slider first (it's the nav target of the content slider).
    $images.slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      fade: true,
      draggable: false,
      swipe: false,
      infinite: true,
      asNavFor: contentSel
    });

    // Content slider carries the controls (dots) and drives the image slider.
    $content.slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      dots: true,
      infinite: true,
      adaptiveHeight: false,
      asNavFor: imagesSel
    });
  }

  // Slick mis-measures sliders that were hidden at init time; refresh on reveal.
  function refresh($item) {
    $item.find('.dl-content, .dl-images').each(function () {
      if ($(this).hasClass('slick-initialized')) {
        $(this).slick('setPosition');
      }
    });
  }

  function activate($item) {
    var $widget = $item.closest('.dl-widget');
    $widget.find('.dl-item').removeClass('is-active');
    $item.addClass('is-active');
    refresh($item);
  }

  $(function () {
    var $items = $('.dl-item');

    // Build a slider pair for every tab.
    $items.each(function () {
      initPanel($(this));
    });

    // Ensure one tab is active on load (first, or any pre-marked one).
    $('.dl-widget').each(function () {
      var $w = $(this);
      var $active = $w.find('.dl-item.is-active').first();
      if (!$active.length) {
        $active = $w.find('.dl-item').first();
      }
      activate($active);
    });

    // Tab header click → switch (desktop) / expand (mobile accordion).
    $(document).on('click', '.dl-tab', function (e) {
      e.preventDefault();
      activate($(this).closest('.dl-item'));
    });

    // Re-measure the active sliders when crossing the mobile/desktop boundary.
    var resizeTimer;
    $(window).on('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        $('.dl-item.is-active').each(function () {
          refresh($(this));
        });
      }, 150);
    });
  });
})(jQuery);
