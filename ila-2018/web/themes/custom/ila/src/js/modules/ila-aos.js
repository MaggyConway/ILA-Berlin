(function ($) {

  Drupal.behaviors.ilaaos = {
    attach: function (context) {
      $(context).find('body').once('aos').each(function () {
        AOS.init({
        });
      });
    }
  }

})(jQuery);
