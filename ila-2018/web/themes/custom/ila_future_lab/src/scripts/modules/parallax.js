import { select } from '../helpers';

export default () => {
  const flHeader = select('.paragraph--type--fl-header-element');
  if (!flHeader) return;

  window.addEventListener('scroll', () => {
    const elem = select('.fl-header-plane');
    const scrollToElem = flHeader.offsetTop - 200;
    const winScrollTop = window.pageYOffset;
    if (winScrollTop > scrollToElem) {
      const scrolled = winScrollTop - scrollToElem;

      const scale = 0.5 + scrolled / 1000;
      // const rotate = 2 - scrolled / 50;
      if (scale <= 1) {
        elem.style.transform = `matrix(${scale}, 0.02, 0, ${scale}, 0, 0)`;
      } else {
        elem.style.transform = `matrix(1, 0, 0, 1, 0, 0)`;
      }
    }
  });
};
