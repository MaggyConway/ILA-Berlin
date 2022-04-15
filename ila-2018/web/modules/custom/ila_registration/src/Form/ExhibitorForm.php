<?php

namespace Drupal\ila_registration\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Exhibitor edit forms.
 *
 * @ingroup ila_registration
 */
class ExhibitorForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\ila_registration\Entity\Exhibitor */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Exhibitor.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Exhibitor.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.ila_registration_exhibitor.canonical', ['ila_registration_exhibitor' => $entity->id()]);
  }

}
