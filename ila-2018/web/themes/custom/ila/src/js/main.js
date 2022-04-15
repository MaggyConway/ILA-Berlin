import stickyHeader from './modules/sticky-header.js';
import accordion from './modules/accordion.js';
import './modules/login-popup';
import './modules/smooth-scroll';
import './modules/placeholder';
import mobileMenu from './modules/mobile-menu';
import './modules/tooltip';
import './modules/count-up';
import './modules/hover-menu';
import './modules/LinkBack';
import './modules/checkbox';
import './modules/bdli-events';
import './modules/career-center';
import './modules/countdown-animation';
import './modules/ila-aos';
import './modules/masonry-init';
import SimpleBar from 'simplebar';
import 'simplebar/dist/simplebar.css';
import './modules/link-back';
import './modules/filter-toggle';
import './modules/popup-win.js';
import './modules/media-search-fix';
import clickVedeoHomePage from './modules/video';

jQuery('document').ready(function () {
  stickyHeader.init();
  accordion.init();
  mobileMenu.init();
  clickVedeoHomePage.init();

  if (!jQuery('html').hasClass('html--background-no-parallax')) {
    jQuery('main').parallax({background: '#F2F2F2'});

    jQuery('#curator-feed-default-feed-en').on('DOMNodeInserted', function () {
      jQuery(window).trigger('resize').trigger('scroll');
    });
  }

  jQuery('.region-header-bottom > .menu--main > .menu--simple-mega-menu > .menu-item.menu-item--expanded').hover(function () {
    jQuery(this).find('.mega-column .paragraph--type--title-image-text').matchHeight();
  });

  if(document.getElementById('section-header')){
    particlesJS.load('section-header', '/themes/custom/ila/src/assets/particlesjs-config.json');
  }

  jQuery('.view-filters').on('click', '.news-filters .filter', function () {
    var textCloseFilter = Drupal.t('Open Filter');
    var textOpenFilter = Drupal.t('Close Filter');

    if(jQuery(this).hasClass('open')) {
      jQuery(this).removeClass('open').text(textCloseFilter);
    }
    else {
      jQuery(this).addClass('open').text(textOpenFilter);
    }
    jQuery('.view-filters').find('.form--inline').toggleClass('opened');
  });

  if(document.getElementById('edit-field-categories-target-id')) {
    new SimpleBar(jQuery('#edit-field-categories-target-id')[0], {
      autoHide: false
    });
  }

  if(document.getElementById('edit-year')) {
    new SimpleBar(jQuery('#edit-year')[0], {
      autoHide: false
    });
  }

  jQuery('.view-conferences-search-api .facets-widget-ila_checkbox ul').each(function(indx) {
    new SimpleBar(jQuery(this)[0], {
      autoHide: false
    });
  });
});
