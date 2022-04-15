(function ($, Drupal, drupalSettings) {
    "use strict";
    /**
     * Attaches the JS countdown behavior
     */
    Drupal.behaviors.jsCountdownTimer = {
        attach: function (context) {
          var ts = new Date(drupalSettings.countdown.unixtimestamp * 1000);
          $('#jquery-countdown-timer').countdown(ts, function(event) {
            var days = Drupal.t('days'),
              hours = Drupal.t('hours'),
              minutes = Drupal.t('minutes'),
              seconds = Drupal.t('seconds');
            var markup = '<div class="countdown-timer">' +
              '<div><span class ="count">%D</span><span class="label">' + days + '</span></div><div class="colon">:</div>' +
              '<div><span class ="count">%H</span><span class="label">' + hours + '</span></div><div class="colon">:</div>' +
              '<div><span class ="count">%M</span><span class="label">' + minutes + '</span></div><div class="colon">:</div>' +
              '<div><span class ="count">%S</span><span class="label">' + seconds + '</span></div>' +
              '</div>';
            $(this).html(event.strftime(markup));
          });
        }
    };
})(jQuery, Drupal, drupalSettings);