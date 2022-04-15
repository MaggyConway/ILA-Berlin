/* eslint-disable */

export default () => {
  const elems = [
    {
      container: '.field--name-field-double-box-item',
      item: '.field--name-field-title',
    },
    {
      container: '.paragraph--type--fl-video-messages',
      item: '.field--name-field-media-reference',
    },
    {
      container: '.paragraph--type--fl-video-box',
      item: '.field--name-name',
    },
  ];

  const maxHFunc = (items, maxHeight)=> {
    items.forEach(item => (item.style.height = 'auto'));
    items.forEach(item => {
      maxHeight = Math.max(item.getBoundingClientRect().height, maxHeight);
    });
    items.forEach(item => (item.style.height = `${maxHeight}px`));
  }

  const equalHeights = (elems)=> {
    elems.forEach(elem => {
      const containers = document.querySelectorAll(elem.container);
      containers.forEach(container => {
        const items = container.querySelectorAll(elem.item);
        let maxHeight = 0;
        maxHFunc(items, maxHeight);
        window.addEventListener('resize', () => {
          maxHFunc(items, maxHeight);
        });
      });
    });
  };
  
  window.addEventListener('DOMContentLoaded', () => {
    equalHeights(elems);
  });
};

/* eslint-enable */
