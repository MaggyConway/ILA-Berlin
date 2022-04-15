(function ($) {

  /**
   * Set active class on Views AJAX filter
   * on selected category
   */
  Drupal.behaviors.exposedfilter_buttons = {
    attach: function(context, settings) {
      var $filter = $('#edit-field-press-media-category-target-id');
      var $submit = $('#views-exposed-form-media-block-6 input.form-submit');
      $('.filter-tab a').on('click', function(event) {
        event.preventDefault();
        $filter
          .val($(event.target).attr('id'))
          .trigger('change');
        $submit.trigger('click');
      });
    }
  };

})(jQuery);
