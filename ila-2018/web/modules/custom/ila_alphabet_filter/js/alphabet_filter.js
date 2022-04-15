(function ($) {

  /**
   * Set disable class on Views AJAX filter
   * on selected category
   */
  Drupal.behaviors.company_list_noactive_buttons = {
    attach: function(context, settings) {
      if ($('#' + drupalSettings.views_block_name.value).length === 0) {
        return;
      }

      var list_values = drupalSettings.filter_fields.values;
      $('.option').each(function(i, elem) {
        var option_text = $(this).text();
        if (option_text === '- Any -' || option_text === '- Alle -') {
          return;
        }
        if ($.inArray(option_text, list_values) === -1) {
          $(this).css({color: '#898989', cursor: 'default'})
            .on('click input', function(event) {
              event.preventDefault();
              event.stopPropagation();
            });
        }
      });
    }
  };

})(jQuery);
