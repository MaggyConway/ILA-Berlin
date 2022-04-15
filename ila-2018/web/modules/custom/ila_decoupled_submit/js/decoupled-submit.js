(function ($, Drupal) {

  var form_selector = '.node-form'
  if (drupalSettings.decoupledSubmit.form_selector) {
    form_selector = drupalSettings.decoupledSubmit.form_selector;
  }

  var $form = $(form_selector);
  var $actions = $('#edit-actions', $form);
  var $target = $('#decoupled-submit-target');

  $actions.appendTo($target).find('submit, button').on('click', function(event) {
    $form.trigger('submit.singleSubmit');
  });

})(jQuery, Drupal);
