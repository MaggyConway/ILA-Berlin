const clickVedeoHomePage = (function($){
  function init() {

    console.log($('video'));

    let btnPlay = $(
      ".block_content--type-video-and-more .field--name-field-sub-title, .field--type-video > .field__label, .field--name-field-video-local > .field__label, .field--name-field-media-video-file > .field__label"
    );

    let video = null;

    btnPlay.on('click', function () {

      video = $(this).next('div').find('video')[0];
      console.log(video);

      // $(video).addClass("full");
      video.controls = true;
      video.requestFullscreen();
      video.play();

      // if (video.paused) {
      //   video.play();
      //   // btnPlay.removeClass('pause');
        
      // } else {
      //   video.pause();
      //   // btnPlay.addClass('pause');
      //   // video.removeClass('full');
      // }
    });

    function fullscreenchanged(event) {
      if (document.fullscreenElement) {
        // console.log(`Element entered fullscreen mode.`);
      } else {
        // console.log('Leaving fullscreen mode.');
        video.controls = false;
        video.pause();
      }
}

    document.addEventListener("fullscreenchange", fullscreenchanged);

    $('.header-mobile-control .open').on('click', function () {
      $('.block-block-content-video_and_more').addClass('mobile-popup-video');
      $('.header-mobile-control .close').addClass('mobile-popup-video');
      video.play();
    })

    $('.header-mobile-control .close').on('click', function () {
      $('.block-block-content-video_and_more').removeClass('mobile-popup-video');
      $('.header-mobile-control .close').removeClass('mobile-popup-video');
      video.pause();
    })
  }

  return {
    init: init
  }
})(jQuery)

export default clickVedeoHomePage;
