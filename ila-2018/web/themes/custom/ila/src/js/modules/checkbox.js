(function ($) {
  /**
   * Set placeholder attribute for Registration form inputs.
   */
  Drupal.behaviors.ilaCheckbox = {
    attach: function (context, settings) {

      var checkbox = $('.path-conference .form-type-checkbox input');
     
      jcf.replace(checkbox);
    }
  };
})(jQuery);
