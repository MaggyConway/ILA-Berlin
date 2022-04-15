(function ($) {
    /**
     *
     */
    Drupal.behaviors.bdlievents = {
        attach: function (context, settings) {
            $('.js-form-item').find('input').each(function(index, el) {
                let $parent = $(el).parent();
                let $label = $parent.find('label');

                $(el).attr('placeholder', $label.text());
            });
        }
    };
})(jQuery);
