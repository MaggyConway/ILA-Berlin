export default () => {
  const tabsContiner = document.querySelector('.paragraph--type--fl-video-box-container');
  if (!tabsContiner) return;

  const tabTitles = tabsContiner.querySelectorAll('.tab-title');
  const tabContent = tabsContiner.querySelectorAll(
    '.field--name-field-paragraph-reference > .field__item',
  );

  tabTitles.forEach((title, index) => {
    const mobileTitle = document.createElement('a');
    mobileTitle.setAttribute('href', '#');
    mobileTitle.className = 'tab-title-mobile';
    mobileTitle.innerHTML = title.innerHTML;
    tabContent[index].prepend(mobileTitle);
  });

  const mobileTitles = tabsContiner.querySelectorAll('.tab-title-mobile');

  const initTab = num => {
    tabTitles[num].classList.add('active');
    tabContent[num].classList.add('active');
    mobileTitles[num].classList.add('active');
  };

  const removeActive = () => {
    tabTitles.forEach(title => title.classList.remove('active'));
    tabContent.forEach(content => content.classList.remove('active'));
    mobileTitles.forEach(content => content.classList.remove('active'));
  };

  initTab(0);

  tabTitles.forEach((title, index) => {
    title.addEventListener('click', e => {
      e.preventDefault();
      if (!title.classList.contains('active')) {
        removeActive();
        title.classList.add('active');
        tabContent[index].classList.add('active');
        mobileTitles[index].classList.add('active');
      }
    });
  });

  mobileTitles.forEach((title, index) => {
    title.addEventListener('click', e => {
      e.preventDefault();
      if (!title.classList.contains('active')) {
        removeActive();
        title.classList.add('active');
        tabContent[index].classList.add('active');
        tabTitles[index].classList.add('active');
      }
    });
  });
};
