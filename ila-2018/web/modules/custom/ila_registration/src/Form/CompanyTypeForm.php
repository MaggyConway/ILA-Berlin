<?php

namespace Drupal\ila_registration\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CompanyTypeForm.
 *
 * @package Drupal\ila_registration\Form
 */
class CompanyTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $ila_registration_company_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $ila_registration_company_type->label(),
      '#description' => $this->t("Label for the Company type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $ila_registration_company_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\ila_registration\Entity\CompanyType::load',
      ],
      '#disabled' => !$ila_registration_company_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $ila_registration_company_type = $this->entity;
    $status = $ila_registration_company_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Company type.', [
          '%label' => $ila_registration_company_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Company type.', [
          '%label' => $ila_registration_company_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($ila_registration_company_type->toUrl('collection'));
  }

}
