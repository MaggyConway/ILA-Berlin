/* eslint-disable */

import $ from 'jquery';

export default () => {
  AOS.init();

  function buttonClickInit() {
    $('.crt-load-more').on('click', function() {
      setTimeout(function() {
        AOS.refresh();
      }, 1000);
    });
  }

  setTimeout(function() {
    AOS.refresh();
    buttonClickInit();
  }, 7000);
};

/* eslint-enable */
