(function ($) {
  /**
   * Disable other checkboxes if one is selected.
   */
  Drupal.behaviors.ilaRequestdRadioButton = {
    attach: function (context, settings) {
      $('#edit-field-stand-price-including-cons--wrapper').find('input').click(function () {
        if ($('#edit-field-req-e-stand-price-without').find('input').is(":checked") == true) {
          $('#edit-field-req-e-stand-price-without').find('input').attr('checked', false);
        }
      });

      $('#edit-field-req-e-stand-price-without').find('input').click(function () {
        if ($('#edit-field-stand-price-including-cons--wrapper').find('input').is(":checked") == true) {
          $('#edit-field-stand-price-including-cons--wrapper').find('input').attr('checked', false);
        }
      });
    }
  };
})(jQuery);
