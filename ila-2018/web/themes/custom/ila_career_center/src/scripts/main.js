import polyfills from './polyfills';
import headerToggle from './modules/header-toggle';
import parallaxHeader from './modules/parallax';
import eventSelector from './modules/event-selector';
import hoverMemu from './modules/hover-menu';
import mobileMenu from './modules/mobile-menu';
import linkBack from './modules/link-back';
import jobsFiltre from './modules/jobs-filtre';
import AOSinit from './modules/aos-init';
import anchorLink from './modules/anchor-link';

(() => {
  polyfills();
  headerToggle();
  parallaxHeader();
  eventSelector();
  hoverMemu();
  mobileMenu();
  linkBack();
  jobsFiltre();
  AOSinit();
  anchorLink();
})();
