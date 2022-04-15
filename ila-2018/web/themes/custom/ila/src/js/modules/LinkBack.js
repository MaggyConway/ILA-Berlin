(function ($) {
  /**
   *
   */
  Drupal.behaviors.LinkBack = {
    attach: function (context, settings) {

      $('a[href="#back"]').unbind("click").click(function(){
        if(document.referrer.indexOf(window.location.hostname) != -1) {
          history.back(-1);
          return false;
        }
      });
    }
  };
})(jQuery);
