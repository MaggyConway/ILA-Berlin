<?php

namespace Drupal\ila_registration\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PersonTypeForm.
 *
 * @package Drupal\ila_registration\Form
 */
class PersonTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $ila_registration_person_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $ila_registration_person_type->label(),
      '#description' => $this->t("Label for the Person type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $ila_registration_person_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\ila_registration\Entity\PersonType::load',
      ],
      '#disabled' => !$ila_registration_person_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $ila_registration_person_type = $this->entity;
    $status = $ila_registration_person_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Person type.', [
          '%label' => $ila_registration_person_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Person type.', [
          '%label' => $ila_registration_person_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($ila_registration_person_type->toUrl('collection'));
  }

}
