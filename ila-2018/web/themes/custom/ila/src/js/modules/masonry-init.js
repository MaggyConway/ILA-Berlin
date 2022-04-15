(function ($) {

  Drupal.behaviors.initMasonry = {
    attach: function (context) {
      window.onload = function () {
        var $grid = $('.field--name-field-link-boxes-ref').masonry();
        AOS.refresh();
        function onLayout() {
          $grid.masonry('layout');
        }
        setTimeout(onLayout, 1000);
        $('.field--name-field-link-boxes-ref').find('img').each(function(index, el) {
          $(el).on('load', function() {
            onLayout();
          });
        });
      }
    }
  }

})(jQuery);