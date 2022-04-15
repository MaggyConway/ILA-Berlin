import polyfills from './polyfills';
import headerToggle from './modules/header-toggle';
import mobileMenu from './modules/mobile-menu';
import parallax from './modules/parallax';
import tabs from './modules/tabs';
import eqHeight from './modules/eq-height';
import anchorLink from './modules/anchor-link';
import stickyMenu from './modules/sticky-menu';

(() => {
  polyfills();
  headerToggle();
  mobileMenu();
  parallax();
  tabs();
  eqHeight();
  anchorLink();
  stickyMenu();
})();
