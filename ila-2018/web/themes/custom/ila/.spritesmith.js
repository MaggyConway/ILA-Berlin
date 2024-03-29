'use strict';

var util = require('util');

module.exports = {
  src: './src/img/sprite/**/*.{png,gif,jpg}',
  destImage: './src/img/sprite.png',
  destCSS: './src/scss/sprite-png.scss',
  padding: 10,
  algorithm: 'binary-tree',
  cssOpts: {
    cssClass: function (item) {
      return util.format('.icon-%s::before', item.name);
    }
  }
};
