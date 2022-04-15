/* eslint-disable */

import $ from 'jquery';

export default () => {
  
  function parallaxScroll() {
    const scrolled = $(window).scrollTop();
    $('#header-text-bg').css('transform', `translateY(${0 - scrolled * 0.15}px)`);
    $('#header-text-outlines-h').css('transform', `translateY(${0 - scrolled * 0.25}px)`);
    $('#header-text-outlines-f').css('transform', `translateY(${0 - scrolled * 0.45}px)`);
    $('#header-text-flugmaschinen').css('transform', `translateX(${-135 + scrolled * 0.25}px)`);
  }
  /* eslint-enable */

  window.onscroll = function() {
    parallaxScroll();
  };
};
