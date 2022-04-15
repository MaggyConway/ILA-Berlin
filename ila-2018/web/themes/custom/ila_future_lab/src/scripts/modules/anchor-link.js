export default () => {
  const scrollElems = document.querySelectorAll(
    '.header__bottom .region-header-bottom .menu--future-lab .menu a',
  );

  scrollElems.forEach(scrollElem => {
    scrollElem.addEventListener('click', e => {
      const url = window.location.pathname;
      const link = e.target.pathname;

      if (url === link && e.target.hash.indexOf('#') !== -1) {
        e.preventDefault();

        ScrollTo(e.target.hash, { duration: 850, offset: -120 });
      }
    });
  });

  const ScrollTo = (target, options) => {
    if (!document.querySelector(target)) return;
    const start = window.pageYOffset;
    const opts = {
      duration: options.duration,
      offset: options.offset || 0,
      callback: options.callback,
      easing: options.easing || _easeInOutQuad,
    };
    const distance =
      typeof target === 'string'
        ? opts.offset + document.querySelector(target).getBoundingClientRect().top
        : target;
    const duration = typeof opts.duration === 'function' ? opts.duration(distance) : opts.duration;
    let timeStart;
    let timeElapsed;

    requestAnimationFrame(time => {
      timeStart = time;
      _loop(time);
    });

    const _loop = time => {
      timeElapsed = time - timeStart;

      window.scrollTo(0, opts.easing(timeElapsed, start, distance, duration));

      if (timeElapsed < duration) requestAnimationFrame(_loop);
      else end();
    };

    const end = () => {
      window.scrollTo(0, start + distance);

      if (typeof opts.callback === 'function') opts.callback();
    };

    function _easeInOutQuad(t, b, c, d) {
      t /= d / 2;
      if (t < 1) return (c / 2) * t * t + b;
      t--;
      return (-c / 2) * (t * (t - 2) - 1) + b;
    }
  };
};
