(function ($) {
  /**
   * Disable other checkboxes if one is selected.
   */
  Drupal.behaviors.ilaRequestFb1Checkboxes = {
    attach: function (context, settings) {
      if ($('#edit-field-request-fb1-type-of-partic-4').is(":checked") == true) {
        $('#edit-field-request-fb1-type-of-partic-1').attr('checked', false).prop('disabled', true);
        $('#edit-field-request-fb1-type-of-partic-2').attr('checked', false).prop('disabled', true);
        $('#edit-field-request-fb1-type-of-partic-3').attr('checked', false).prop('disabled', true);
      }
      $('#edit-field-request-fb1-type-of-partic-4').click(function() {
        if (this.checked == true) {
          $('#edit-field-request-fb1-type-of-partic-1').attr('checked', false).prop('disabled', true);
          $('#edit-field-request-fb1-type-of-partic-2').attr('checked', false).prop('disabled', true);
          $('#edit-field-request-fb1-type-of-partic-3').attr('checked', false).prop('disabled', true);
        }
        else {
          $('#edit-field-request-fb1-type-of-partic-1').attr('checked', false).prop('disabled', false);
          $('#edit-field-request-fb1-type-of-partic-2').attr('checked', false).prop('disabled', false);
          $('#edit-field-request-fb1-type-of-partic-3').attr('checked', false).prop('disabled', false);
        }
      });
    }
  };
})(jQuery);