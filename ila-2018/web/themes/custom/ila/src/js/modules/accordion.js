let accordion = (function($){
  let autoPlayTimer = 6 * 1000;

  function init() {
    _setUpListeners();
  }

  function _setUpListeners() {
    let $accordion = $('.accordion');

    $accordion.hover(() => {
      $accordion.toggleClass('disable-auto');
    });

    $accordion.on('click', '.accordion__item', function(e) {
      if ($(e.target).is('a')) {
        return;
      }
      e.preventDefault();
      changeActiveItem($(this));
    });

    $(document).keydown(function(e) {
      switch(e.which) {
        case 37: // left
          e.preventDefault();
          changeActiveItem(getPreviousItem());
          break;

        case 39: // right
          e.preventDefault();
          changeActiveItem(getNextItem());
          break;

        default: return; // exit this handler for other keys
      }
    });

    $(window).on('resize', function() {
      setMargin();
    })

    setInterval(() => {
      if (!$accordion.hasClass('disable-auto')) {
        changeActiveItem(getNextItem());
      }
    }, autoPlayTimer);

    function getNextItem() {
      let $nextItem = $accordion.find('.accordion__item.is-active').next();

      if ($nextItem.length === 0) {
        $nextItem = $accordion.find('.accordion__item').first();
      }

      return $nextItem;
    }

    function getPreviousItem() {
      let $prevItem = $accordion.find('.accordion__item.is-active').prev();

      if ($prevItem.length === 0) {
        $prevItem = $accordion.find('.accordion__item').last();
      }

      return $prevItem;
    }

    function isMobile() {
      return (window.matchMedia('(max-width: 768px)').matches ? true : false)
    }

    function setMargin() {
      let $accordion = $('.accordion');
      let $active = $accordion.find('.is-active');

      if ($active.length && isMobile()) {
        let textBlockHeight = $active.find('.accordion__text').outerHeight();
        $accordion.css('padding-bottom', textBlockHeight + 'px');
      } else {
        $accordion.css('padding-bottom', '0');
      }
    }

    function changeActiveItem($item) {
      let $otherTabs = $item.siblings('.accordion__item');

      if (!$item.hasClass('is-active')) {
        $otherTabs.removeClass('is-active');
        $item.addClass('is-active');
        setMargin()
      }
    }


  }

  return {
    init: init
  }
})(jQuery);

export default accordion;
