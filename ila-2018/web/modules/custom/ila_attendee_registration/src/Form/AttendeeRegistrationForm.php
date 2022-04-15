<?php

namespace Drupal\ila_attendee_registration\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\Entity;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Attendee registration edit forms.
 *
 * @ingroup ila_attendee_registration
 */
class AttendeeRegistrationForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\ila_attendee_registration\Entity\AttendeeRegistration */
    $form = parent::buildForm($form, $form_state);

    if (!$this->entity->isNew()) {
      $form['new_revision'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Create new revision'),
        '#default_value' => FALSE,
        '#weight' => 10,
      ];
    }

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $entity = &$this->entity;
    $current_user = \Drupal::currentUser();

    // Save as a new revision if requested to do so.
    if (!$form_state->isValueEmpty('new_revision') && $form_state->getValue('new_revision') != FALSE) {
      $entity->setNewRevision();

      // If a new revision is created, save the current user as revision author.
      $entity->setRevisionCreationTime(\Drupal::time()->getRequestTime());
      $entity->setRevisionUserId(\Drupal::currentUser()->id());
    } else {
      $entity->setNewRevision(FALSE);
    }

    $status = parent::save($form, $form_state);
    // For anon users.
    $session = \Drupal::request()->getSession();
    $id = $entity->id();
    $session->set('registration_id', $id);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Created the %label Attendee registration.',
          [
          '%label' => $entity->label(),
          ]), 'status');
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Saved the %label Attendee registration.',
          [
          '%label' => $entity->label(),
          ]), 'status');
    }

    $form_state->setRedirect('entity.attendee_registration.canonical', ['attendee_registration' => $entity->id()]);
  }

}
