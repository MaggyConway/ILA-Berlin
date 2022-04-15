/* eslint-disable */

import $ from 'jquery';

/* eslint-enable */

export default () => {
  $('.link-history-back').click(function(e) {
    e.preventDefault();
    var host = window.location.host;
    var referrer = document.referrer;
    if (referrer.includes(host)) {
      if (history.length > 1) {
        history.back(-1);
      }
    }
  });
};
