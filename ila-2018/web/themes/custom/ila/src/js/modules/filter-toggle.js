(function ($) {

  var textCloseFilter = Drupal.t('Open Filter');
  var textCloseFilterMedia = Drupal.t('To The Categories');
  var textOpenFilter = Drupal.t('Close Filter');

  var eventOpenFilter = 'open';
  var updatedEventFilter = 'close';
  var statusEventFilter = localStorage.getItem('eventOpenFilter');

  if (statusEventFilter === eventOpenFilter) {
    $('body').addClass('conference-filters-opened');
    $('.conference-filters .filter-open-btn').text(textOpenFilter);
  }

  $(document).on('click', '.conference-filters .filter-open-btn', function (e) {
    e.preventDefault();

    if ($('body').hasClass('conference-filters-opened')) {
      localStorage.setItem('eventOpenFilter', updatedEventFilter);
      $('body').removeClass('conference-filters-opened');

      if($(this).closest('.view').hasClass('view-press-media-search')) {
        $(this).text(textCloseFilterMedia);
      } else {
        $(this).text(textCloseFilter);
      }
    }

    else {
      localStorage.setItem('eventOpenFilter', eventOpenFilter);
      $('body').addClass('conference-filters-opened');
      $(this).text(textOpenFilter);
    }
  });

  $('.path-all-conferences').on('click', '.conference-filters .filter-close-btn', function (e) {
    e.preventDefault();
  });

  $('.path-conference-speakers').on('click', '.speaker-filters .filter-open-btn', function (e) {
    e.preventDefault();
    $('body').toggleClass('opened-glossary');
  });

})(jQuery);
