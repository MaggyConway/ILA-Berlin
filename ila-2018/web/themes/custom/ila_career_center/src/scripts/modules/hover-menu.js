/* eslint-disable */

import $ from 'jquery';

export default () => {
  $('document').ready(function () {
    $('.region-header-top-main-nav > .menu--main > .menu--simple-mega-menu > .menu-item.menu-item--expanded').unbind('mouseenter.hover-mega-menu').bind('mouseenter.hover-mega-menu', function() {
      var megaMenu = $(this).find('.mega-menu-wrapper');
      megaMenu.css({
        left: '50%'
      });
      var winWidth = $(window).width();
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

/* eslint-enable */
