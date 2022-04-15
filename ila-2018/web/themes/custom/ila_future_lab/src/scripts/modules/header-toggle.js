import { select } from '../helpers';

export default () => {
  const menuButton = select('.header__button');
  if (!menuButton) return;
  const mobileMenuButton = select('.mobile-button__trigger');

  const togglerMenu = () => select('.header__top').classList.toggle('opened');
  const togglerButtonWrap = () => select('.header__button-wrap').classList.toggle('opened');

  function toggleText() {
    const text = select('.header__button').firstChild;
    const textMenuOpen = Drupal.t('Menu');
    const textMenuClose = Drupal.t('Close');
    text.data = text.data === textMenuOpen ? textMenuClose : textMenuOpen;
  }

  menuButton.addEventListener('click', () => {
    togglerMenu();
    togglerButtonWrap();
    toggleText();
  });

  mobileMenuButton.addEventListener('click', () => {
    togglerMenu();
  });
};
