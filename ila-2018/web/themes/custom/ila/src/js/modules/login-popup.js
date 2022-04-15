(function ($) {
  $(() => {
    let $popup = $('.layout-popup');

    $popup.on('click', (event) => {
      if (!$(event.target).closest('.layout-popup .block').length) {
        $popup.hide();
      }
    });

    $('a.login-link').on('click', (event) => {
      event.preventDefault();
      $popup.show();
    });
  });
})(jQuery);
