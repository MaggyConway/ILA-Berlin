(function ($) {
  Drupal.behaviors.ilaHoverMenu = {
    attach: function (context, settings) {
      jQuery('document').ready(function () {
        jQuery('.region-header-bottom > .menu--main > .menu--simple-mega-menu > .menu-item.menu-item--expanded').unbind('mouseenter.hover-mega-menu').bind('mouseenter.hover-mega-menu', function() {
          var megaMenu = jQuery(this).find('.mega-menu-wrapper');
          megaMenu.css({
            left: '50%'
          });
          var winWidth = jQuery(window).width();
          var leftPosition = megaMenu.offset().left;
          var rightPosition = megaMenu.offset().left + megaMenu.innerWidth();
        
          if (leftPosition < 0) {
            megaMenu.css({
              left: 'calc(50% + '+ (leftPosition*(-1)) +'px)'
            });
          } else {
            if (rightPosition > winWidth) {
              var right = rightPosition - winWidth;
              megaMenu.css({
                left: 'calc(50% - '+ right +'px)'
              });
            }
          }
        });
      });
    }
  };
})(jQuery);
  
