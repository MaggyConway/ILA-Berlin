export default () => {
  const header = document.querySelector('.header');
  const navbar = document.querySelector('.header__bottom');
  const menuButton = document.querySelector('.header__button');
  let sticky = navbar.getBoundingClientRect().top;

  if (!navbar) return;

  const stickyMenu = () => {
    if (window.pageYOffset >= sticky && document.documentElement.clientWidth > 768) {
      header.classList.add('is-sticky');
    } else {
      header.classList.remove('is-sticky');
    }
  };

  menuButton.addEventListener('click', () => {
    document.querySelector('.header__top').addEventListener('transitionend', () => {
      sticky = navbar.getBoundingClientRect().top;
    });
  });

  window.onscroll = () => {
    stickyMenu();
  };
};
