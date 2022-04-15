(function ($) {
  Drupal.behaviors.ilaCountUp = {
    attach: function (context, settings) {
      jQuery('document').ready(function () {
        var options_en = {
              useEasing: true, 
              useGrouping: true, 
              separator: ',',
              decimal: ','
            };
          
            var options2_en = {
              useEasing: true, 
              useGrouping: true, 
              separator: ',',
              decimal: ','
            };
    
            var options_de = {
              useEasing: true,
                useGrouping: true,
                separator: '.',
                decimal: '.'
            };
            var options2_de = {
                useEasing: true,
                useGrouping: true,
                separator: '.',
                decimal: '.'
            };

        function countUpFunc() {
           
            jQuery(".fact__number").once('fact-one').each(function (index) {
                var numAnim;
                var num = jQuery("#fact-number-" + (index + 1)).attr('data-to');
                var dsa = jQuery("#fact-number-" + (index + 1)).attr('data-greater-than');
                var language = $('html').attr('lang');
                if (dsa === "false") {
                    if (language === 'en') {
                        numAnim = new CountUp("fact-number-" + (index + 1), 0, num, 0, 2, options_en);
                    }
                    else {
                        numAnim = new CountUp("fact-number-" + (index + 1), 0, num, 0, 2, options_de);
                    }
                } else {
                    if (language === 'en') {
                        numAnim = new CountUp("fact-number-" + (index + 1), 0, num, 0, 2, options2_en);
                    }
                    else {
                        numAnim = new CountUp("fact-number-" + (index + 1), 0, num, 0, 2, options2_de);
                    }
                }
                numAnim.start();
            });
        }

        var check = true;
        if ($(".block-views-blockfacts-block-facts").length !== 0) {
            jQuery(document).on({
                scroll: function () {
                    var s_top = jQuery(window).scrollTop() + jQuery(window).height() - 70;
                    var yes = jQuery(".block-views-blockfacts-block-facts").offset().top;
                    if (s_top > yes && check) {
                        countUpFunc();
                        check = false;
                    }
                }, ready: function () {
                    var s_top = jQuery(window).scrollTop() + jQuery(window).height() - 70;
                    var yes = jQuery(".block-views-blockfacts-block-facts").offset().top;
                    if (s_top > yes && check) {
                        countUpFunc();
                        check = false;
                    }
                }
            });
        }
      });
    }
  };
})(jQuery);
