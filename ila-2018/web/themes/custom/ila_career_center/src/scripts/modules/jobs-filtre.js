/* eslint-disable */

import $ from 'jquery';

/* eslint-enable */

export default () => {
  $('.paragraph-content--view-display-id-careercenter_job_offers_search').on(
    'click',
    '.view-careercenter-job-offers .show-filters',
    function(e) {
      e.preventDefault();
      $('.view-careercenter-job-offers > .view-header').toggleClass('opened');
    },
  );
};
