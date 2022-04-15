(function ($) {
  Drupal.behaviors.colorboxInline = {
    attach: function (context, drupalSettings) {
      $('.video-popup__trigger', context).once().click(function (event) {
        event.preventDefault();
        var $link = $(this);
        var settings = $.extend(drupalSettings.ila_video_popup_colorbox, drupalSettings.ila_video_popup);
        $link.colorbox(settings);
      });
    }
  };
})(jQuery);
