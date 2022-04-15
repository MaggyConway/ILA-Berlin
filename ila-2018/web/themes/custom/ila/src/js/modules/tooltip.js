/**
 * @file
 * Bootstrap Tooltips.
 */

var Drupal = Drupal || {};

(function ($, Drupal, Bootstrap) {
  "use strict";

  /**
   * Extend the Bootstrap Tooltip plugin constructor class.
   */
  Bootstrap.extendPlugin('tooltip', function (settings) {
    return {
      DEFAULTS: {
        animation: !!settings.tooltip_animation,
        html: !!settings.tooltip_html,
        placement: settings.tooltip_placement,
        selector: settings.tooltip_selector,
        trigger: settings.tooltip_trigger,
        delay: parseInt(settings.tooltip_delay, 10),
        container: settings.tooltip_container
      }
    };
  });

  /**
   * Bootstrap Tooltips.
   *
   * @todo This should really be properly delegated if selector option is set.
   */
  Drupal.behaviors.bootstrapTooltipsIla = {
    attach: function (context) {

        var elements = $(context).find('.js-form-item .description').toArray();

        var popup = document.createElement('div');
        $(popup)
        .addClass('popup')
        .attr('id', 'descriptionPopup')
        .css({
          "position": "fixed",
          "z-index": "1000",
          "left": "0",
          "top": "0",
          "width": "100%",
          "height": "100vh",
          "background": "rgba(47, 67, 74, .75)",
          "display": "flex",
        })
        .html('<div class="popup__wrap"><div class="popup__content"><div class="popup__title">' + Drupal.t('Note') + '</div><div class="popup__text"></div><button type="button" id="popupCloseBtn" class="popup__btn">Ok</button></div></div>')
        .hide()
        .prependTo('body');

      $('#ui-id-16 .wrapp-2-cols .field-group-html-element h3').append('<span class="popup__show-btn" data-description="' + Drupal.t('Please fill in the company data of the firm acting on behalf of the main exhibitor (e.g. agency).') + '">i</span>');
        
        for (var i = 0; i < elements.length; i++) {
          var $element = $(elements[i]);
          var description = $element.text();
          var $label = $element.parent('div');
          var $form = $element.parent('.js-form-item');
          $element.remove();
          if (description) {
            $label.append('<span class="popup__show-btn" data-description="' + description.replace(/"/g, '”') + '">i</span>');
            $form.addClass('form-item-with-popup');
          }
        }
        $('.popup__show-btn').on('click', function() {
          var description = $(this).data('description');
          var $popup = $('#descriptionPopup');
          if (description) {
            $popup.find('.popup__text').text(description);
            $popup.fadeIn(300);
          }
        });
  
        $('#descriptionPopup').on('click',function(e) {
          e.preventDefault();
          var $target = $(e.target);
          if ((!$target.parents().is('.popup__content') && !$target.is('.popup__content')) || $target.is('#popupCloseBtn')) {
            $(this).closest('#descriptionPopup').fadeOut(200);
          }
        });
    },
  };

})(window.jQuery, window.Drupal, window.Drupal.bootstrap);