/* eslint-disable */

import $ from 'jquery';

/* eslint-enable */

export default () => {
  $('.menu--simple-mega-menu').on('click', 'h2', function() {
    if (window.matchMedia('(max-width: 975px)').matches) {
      $(this)
        .next('.menu')
        .stop(true, true)
        .slideToggle(300);
    }
  });
};
