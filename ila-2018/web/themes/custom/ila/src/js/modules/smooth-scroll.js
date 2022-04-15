(function ($) {
  $(() => {
    $('a').on('click', function (e) {
      let hash = $(this).prop('hash');

      if (hash && $(hash).length > 0) {
        e.preventDefault();

        smoothScroll(hash);
      }
    });

    $(window).on('load', function() {
      let hash = this.location.hash;
      if (hash && $(hash).length > 0) {
        smoothScroll(hash);
      }
    });
    function smoothScroll(hash) {
      let menuOffset = 106;
      $('html, body').animate({
        scrollTop: $(hash).offset().top - menuOffset
      });
    }
  });
})(jQuery);
