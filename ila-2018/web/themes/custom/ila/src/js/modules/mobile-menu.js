var mobileMenu = (function($){
  function init() {
    const $body = $('body');
    const $header = $('.header__bottom');
    const btnText = 'Menu';
    const button =
      `<div class="mobile-button">
        <button type="button" class="mobile-button__trigger">
          ${Drupal.t(btnText)}
          <i class="mobile-button__icon">
            <i class="mobile-button__line mobile-button__line--1">-</i>
            <i class="mobile-button__line mobile-button__line--2">-</i>
            <i class="mobile-button__line mobile-button__line--3">-</i>
          </i>
        </button>
      </div>`;
    const $menu = $('.region-header-bottom');

    $(button).prependTo($header);

    $('.mobile-button').on('click', function(e) {
      e.preventDefault();
      const $this = $(this);
      const duration = 300;

      $('body').toggleClass('mobile-open');
      $header.toggleClass('open');

      $('.block-block-content-video_and_more').removeClass('mobile-popup-video');
      $('.header-mobile-control .close').removeClass('mobile-popup-video');
    })

    $('.menu--simple-mega-menu').on('click', 'h2', function() {
      if (window.matchMedia('(max-width: 975px)').matches) {
        $(this)
          .next('.menu')
          .stop(true, true)
          .slideToggle(300);
      }
    });

    $(window).on('resize', function() {
      if (window.matchMedia('(max-width: 975px)').matches) {
        // $menu.hide();
      } else {
        // $menu.css('display', 'flex');
      }
      $body.removeClass('mobile-open');
      $header.removeClass('open');
    })

    $(document).on('click', function(e) {
      if (window.matchMedia('(max-width: 975px)').matches && (!$(e.target).closest('.header__bottom').length || $(e.target).closest('.main-menu__item').length)) {
        $body.removeClass('mobile-open');
        // $menu.slideUp(300, ()=> {
        //   $header.removeClass('open');
        // })
      }
    })
  }

  return {
    init: init
  }
})(jQuery)

export default mobileMenu;