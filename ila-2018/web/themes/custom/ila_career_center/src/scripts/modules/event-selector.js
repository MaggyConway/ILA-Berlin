/* eslint-disable */

import $ from 'jquery';

export default () => {

  $('.view-careercenter-events .view-grouping-header').each(function() {
    let textStr = $(this).text();
    $('.view-careercenter-events .select-date-wrapper ul').append('<li><a>'+textStr+'</a></li>');
  });

  function DropDown(el) {
    this.dd = el;
    this.placeholder = this.dd.children('span');
    this.opts = this.dd.find('ul li');
    this.val = this.dd.find('ul li:first-of-type a').text();
    this.index = -1;
    this.initEvents();
    this.dd.closest('.select-date-wrapper').siblings('.view-grouping').filter(':first').addClass('show');
  }

  DropDown.prototype = {
    initEvents: function () {
      let obj = this;
      obj.placeholder.text(obj.val);
      obj.dd.on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          $(this).toggleClass('active');
      });
      obj.opts.on('click', function () {
          let opt = $(this);
          obj.val = opt.text();
          obj.index = opt.index();
          obj.placeholder.text(obj.val);
          opt.siblings().removeClass('selected');
          opt.closest('.select-date-wrapper').siblings('.view-grouping').removeClass('show');
          opt.filter(':contains("' + obj.val + '")').addClass('selected');
          opt.closest('.select-date-wrapper').siblings('.view-grouping').find('.view-grouping-header').filter(':contains("' + obj.val + '")').parent('.view-grouping').addClass('show');
          AOS.refresh();
      }).change();
    },
    getValue: function () {
      return this.val;
    },
    getIndex: function () {
      return this.index;
    }
  };

  $(function () {
    let dd1 = new DropDown($('.select-date-wrapper'));
    $(document).click(function () {
      $('.select-date-wrapper').removeClass('active');
    });
  });
};

/* eslint-enable */
