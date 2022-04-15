(function ($) {

  Drupal.behaviors.linkBack = {
    attach: function (context) {
      $(document).on('click', '.link-history-back', function (e) {
        e.preventDefault();
        var host = window.location.host;
        var referrer = document.referrer;
        if (referrer.includes(host)) {
          if (history.length > 1) {
            history.back(-1);
          }
        }
      });
    }
  }

})(jQuery);