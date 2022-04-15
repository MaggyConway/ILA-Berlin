var stickyHeader = (function($){
  function init() {
    var $header = $('.header');
    var $headerBottom = $header.find('.header__bottom');
    var heroHeight = $('.block-block-content-video').height() - 150;
    if(isNaN(heroHeight)) {
      heroHeight = 0;
    }
    var scroll = $(document).scrollTop();
    getSticky();

    $(document).on('scroll', function() {
      scroll = $(this).scrollTop();
      getSticky();

    });

    function getSticky() {
      if (heroHeight <= scroll && !$header.hasClass('is-sticky')) {
        $headerBottom.stop(true,true).show();
        $header.addClass('is-sticky');
      } else
      if (heroHeight > scroll && $header.hasClass('is-sticky')) {
        $headerBottom.fadeOut(300, function() {
          $header.removeClass('is-sticky');
          $headerBottom.stop(true,true).show();
        });
      }
    }

    $(document).on('scroll', function() {
      scroll = $(this).scrollTop();

      if (scroll > 0) {
        $header.addClass('sticky-header-bottom');
      } else {
        $header.removeClass('sticky-header-bottom')
      }

    });
  }

  return {
    init: init
  }
})(jQuery)

export default stickyHeader;
