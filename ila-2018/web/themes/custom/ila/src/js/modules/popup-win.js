(function ($) {

    Drupal.behaviors.popupwindows_buttons = {
        attach: function(context, settings) {
            if ($('.media-popup-win').length !== 0) {
                $('.media-popup-win').fadeOut(0);

                var click_button = '.text-field-image, .text-title';
                var winWidth = $(window).width();
                var winHeight = $(window).height();

                var disWidth = winWidth - 200;
                var disHeight = winHeight / 2;

                var currentImage;

                $(click_button).click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var className = $(this).attr('class').split(' ')[1];

                    var arrayViewsRow = $('.media-image .view-content .views-row');
                    if (arrayViewsRow.length > 1) {
                        var buttonLeft = '<span class="media-popup-win__button media-popup-win__button--left"></span>';
                        var buttonRight = '<span class="media-popup-win__button media-popup-win__button--right"></span>';
                        $('.media-popup-win .media-popup-img-wrapper').once('button-left-once').append(buttonLeft);
                        $('.media-popup-win .media-popup-img-wrapper').once('button-right-once').append(buttonRight);
                    }

                    currentImage = $('div.media-popup-win.' + className).parents('.views-row');

                    $('div.media-popup-win.' + className).parent('.field-content').fadeIn(0);

                    $('div.media-popup-win.' + className).fadeIn(200, function() {
                        var desc = $('div.media-popup-win.' + className + ' .media-popup-description-wrapper').innerHeight();
                        var image = $('div.media-popup-win.' + className + ' .media-popup-img-wrapper img').innerHeight();

                        $('div.media-popup-win.' + className + ' .media-popup-description-wrapper').css({
                            'max-height': image + 'px',
                            'overflow': 'auto'
                        });
                    });

                    $(window).resize(function() {
                        var desc = $('div.media-popup-win.' + className + ' .media-popup-description-wrapper').innerHeight();
                        var image = $('div.media-popup-win.' + className + ' .media-popup-img-wrapper img').innerHeight();

                        $('div.media-popup-win.' + className + ' .media-popup-description-wrapper').css({
                            'max-height': image + 'px',
                            'overflow': 'auto'
                        });
                    });
                    $(window).resize();
                });


                $('div.media-popup-win').once('click-button-left').on('click', '.media-popup-win__button--left', function() {
                    var $this = $(this).parents('.views-row');
                    var $thisModal = $(this).parents('.media-popup-win');
                    var $predElem = $this.prev();

                    if ($predElem.length === 0) {
                        $predElem = $this.parent('.view-content').find('> .views-row').last();
                    }

                    $('.media-popup-win').fadeOut(0);
                    $('.media-popup-win').parent('.field-content').fadeOut(0);

                    $predElem.find('.views-field-nothing-2 .field-content').fadeIn(0);
                    $predElem.find('.media-popup-win').fadeIn(200, function () {
                        var desc = $predElem.find('.media-popup-description-wrapper').innerHeight();
                        var image = $predElem.find('.media-popup-img-wrapper img').innerHeight();

                        $predElem.find('.media-popup-description-wrapper').css({
                            'max-height': image + 'px',
                            'overflow': 'auto'
                        });
                    });

                    currentImage = $predElem;
                });

                $('div.media-popup-win').once('click-button-right').on('click', '.media-popup-win__button--right', function() {
                    var $this = $(this).parents('.views-row');
                    var $thisModal = $(this).parents('.media-popup-win');
                    var $nextElem = $this.next();

                    if ($nextElem.length === 0) {
                        $nextElem = $this.parent('.view-content').find('> .views-row').first();
                    }

                    $('.media-popup-win').fadeOut(0);
                    $('.media-popup-win').parent('.field-content').fadeOut(0);

                    $nextElem.find('.views-field-nothing-2 .field-content').fadeIn(0);
                    $nextElem.find('.media-popup-win').fadeIn(200, function () {
                        var desc = $nextElem.find('.media-popup-description-wrapper').innerHeight();
                        var image = $nextElem.find('.media-popup-img-wrapper img').innerHeight();

                        $nextElem.find('.media-popup-description-wrapper').css({
                            'max-height': image + 'px',
                            'overflow': 'auto'
                        });
                    });

                    currentImage = $nextElem;
                });


                $('html').unbind('keyup.popup-navigation').bind('keyup.popup-navigation', function(e){
                    if (currentImage != null && currentImage != undefined) {
                        var arrayBlockImage = $('.media-image .view-content .views-row');
                        if (arrayBlockImage.length > 1) {
                            if (e.which === 37) {
                                currentImage.find(".media-popup-win__button--left").trigger('click');
                            }
                            if (e.which === 39) {
                                currentImage.find(".media-popup-win__button--right").trigger('click');
                            }
                            if (e.which === 27) {
                                $('.media-popup-win').fadeOut(0);
                                $('.media-popup-win').parent('.field-content').fadeOut(0);
                                currentImage = null;
                            }
                        }
                    }
                });


                $(document).mouseup(function (e) {
                    var div = $('.media-popup-win');
                    if (!div.is(e.target)
                        && div.has(e.target).length === 0) {
                        div.fadeOut(0);
                        div.parent('.field-content').fadeOut(0);
                        currentImage = null;
                    }
                });

                $('.close-button').click(function (e) {
                    $('.media-popup-win').fadeOut(0);
                    $('.media-popup-win').parent('.field-content').fadeOut(0);
                    currentImage = null;
                });
            }

            if ($('.media-audio-popup').length !== 0 || $('.media-video-popup').length !== 0) {
                $('.media-audio-popup').fadeOut(0);
                $('.media-video-popup').fadeOut(0);
                winWidth = $(window).width();
                winHeight = $(window).height();
                var url_audio = $('.views-field-field-file-1 .field-content a').attr('href');
                var url_video = $('.field-video-file-download a').attr('href');
                var video_html = '<div class="video-wrapper"><video controls><source src="' + url_video + '"></video></div>';
                var audio_html = '<div class="audio-wrapper"><audio controls><source src="' + url_audio + '"></audio></div>';
                $('.media-video-file').once('video-once').append(video_html);
                $('.media-audio-file').once('audio-once').append(audio_html);

                $('.views-field-field-file-1').click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var className = $(this).attr('class').split(' ')[2];
                    var url_audio = $('div.field-content.' + className + ' a').attr('href');
                    var audio_html = '<div class="audio-wrapper"><audio controls><source src="' + url_audio + '"></audio></div>';
                    $('div.media-audio-popup.' + className).parent('span').fadeIn(0);
                    if ($('.audio-wrapper').length === 0) {
                        $('.media-audio-file').append(audio_html);
                    }
                    $('div.media-audio-popup.' + className).fadeIn(200);
                });
                $('.field-video-file-download').click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var className = $(this).attr('class').split(' ')[1];
                    var url_video = $('div.field-video-file-download.' + className + ' a').attr('href');
                    var video_html = '<div class="video-wrapper"><video controls><source src="' + url_video + '"></video></div>';
                    $('div.media-video-popup.' + className).parent('span').fadeIn(0);
                    if ($('.video-wrapper').length === 0) {
                        $('.media-video-file').append(video_html);
                    }
                    $('div.media-video-popup.' + className).fadeIn(200);
                });
                $(document).mouseup(function (e) {
                    var div = $('.media-audio-popup');
                    if (!div.is(e.target)
                        && div.has(e.target).length === 0) {
                        div.fadeOut(0);
                        div.parent('span').fadeOut(0);
                        $('.audio-wrapper').remove();
                    }
                    div = $('.media-video-popup');
                    if (!div.is(e.target)
                        && div.has(e.target).length === 0) {
                        div.fadeOut(0);
                        div.parent('span').fadeOut(0);
                        $('.video-wrapper').remove();
                    }
                });
                $('.close-button').click(function (e) {
                    $('.video-wrapper').remove();
                    $('.audio-wrapper').remove();
                    $('.media-audio-popup').fadeOut(0);
                    $('.media-video-popup').fadeOut(0);
                    $('div.media-audio-popup').parent('span').fadeOut(0);
                    $('div.media-video-popup').parent('span').fadeOut(0);
                });
            }
        }
    };

})(jQuery);
