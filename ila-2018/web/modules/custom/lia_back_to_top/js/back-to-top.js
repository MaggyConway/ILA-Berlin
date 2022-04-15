(function ($, Drupal) {
  var $root = $("html, body");
  var height = $(window).innerHeight();
  var $btnTop = $('.back-to-top');

  $(document).on('scroll', function() {
    if ($(this).scrollTop() > height) {
      $btnTop.addClass('visible');
    } else {
      $btnTop.removeClass('visible');
    }
  })

  $btnTop.on('click', '.back-to-top__link', function (e) {
    e.preventDefault();

    $root.animate({scrollTop: 0}, 500);
  });
})(jQuery, Drupal);
