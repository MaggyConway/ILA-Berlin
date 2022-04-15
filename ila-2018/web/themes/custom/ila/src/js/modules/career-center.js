(function ($) {
  $(() => {
    if ($('.career-center-visitors').length) {
      const wrapper = $('.career-center-visitors');
      /** Banner legnth */
      const banner = $('.career-center-visitors .paragraph--type--image-full');
      const url = banner.find('img').attr('src');
      banner.css('background-image', `url(${url})`);
      /** News Images */
      const news = wrapper.find('.paragraph--type--career-center-visitors-news');
      const imageNews = news.find('.paragraph--type--image-row').parent();
      imageNews.each((index, node) => {
        const img = $(node).find('img').attr('src');
        $(node).css('background-image', `url(${img})`);
      });
    }

    if ($('.path-career-center').length) {
      let stampElems = $('.field--name-field-cc-news-components > .field__item:lt(4)');
      stampElems.each((index, node) => {
        $(node).addClass(`stamp stamp-${index + 1}`);

        if (index === 3) {
          $(window).on('resize', function () {
            const fbGapTop = $(stampElems[1]).height() + $(stampElems[2]).height() - 250 + 40;
            $(node).css('margin-top', fbGapTop);
          });

          const fbGapTop = $(stampElems[1]).height() + $(stampElems[2]).height() - 250 + 40;
          $(node).css('margin-top', fbGapTop);
        }
      });

      setTimeout(function () {
        jQuery('.field--name-field-cc-news-components').masonry({
          itemSelector: '.field--name-field-cc-news-components > .field__item:not(.stamp)',
          horizontalOrder: false,
          stamp: '.stamp'
        });

        let isSwaped = false;

        if ($(window).width() < 976 && !isSwaped) {
          $('.stamp-1').before($('.stamp-2'));
          isSwaped = true;
        } else if ($(this).width() > 976 && isSwaped) {
          $('.stamp-1').after($('.stamp-2'));
          isSwaped = false;
        }

        $(window).on('resize', function () {
          if ($(window).width() < 976 && !isSwaped) {
            $('.stamp-1').before($('.stamp-2'));
            isSwaped = true;
          } else if ($(this).width() > 976 && isSwaped) {
            $('.stamp-1').after($('.stamp-2'));
            isSwaped = false;
          }
        });
      }, 200);

      /** Add */
      $('.field--name-field-cc-news-components > .field__item > .paragraph--type--cc-facebook-post').parent().addClass('field__item--fb');
    }
  });
})(jQuery);
