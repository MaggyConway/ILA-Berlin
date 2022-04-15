(function ($) {
  /**
   * Set placeholder attribute for Registration form inputs.
   */
  Drupal.behaviors.ilaPlaceholders = {
    attach: function (context, settings) {
      $('form.user-register-form, form.attendee-registration-form').find('input').each(function(index, el) {
        let $parent = $(el).parent();
        let $label = $parent.find('label');

        $(el).attr('placeholder', $label.text());
      });
    }
  };
})(jQuery);
