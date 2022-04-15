(function ($) {
  /**
   * Disable other checkboxes if one is selected.
   */
  Drupal.behaviors.ilaRequestdRadioButton = {
    attach: function (context, settings) {
      $('#edit-field-request-d-stands-without-s').find('input').click(function () {
        if ($('#edit-field-stand-price-without-stand-').find('input').is(":checked") == true) {
          $('#edit-field-stand-price-without-stand-').find('input').attr('checked', false);
        }
      });

      $('#edit-field-stand-price-without-stand-').find('input').click(function () {
        if ($('#edit-field-request-d-stands-without-s').find('input').is(":checked") == true) {
          $('#edit-field-request-d-stands-without-s').find('input').attr('checked', false);
        }
      });
    }
  };
})(jQuery);