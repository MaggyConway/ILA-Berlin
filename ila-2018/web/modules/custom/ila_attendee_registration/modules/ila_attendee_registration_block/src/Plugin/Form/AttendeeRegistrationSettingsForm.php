<?php

namespace Drupal\ila_attendee_registration_block\Plugin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Path\PathValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Register confirmation form.
 */
class AttendeeRegistrationSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'attendee_registration_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'ila_attendee_registration_block.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    /** @var \Drupal\Core\Config\ImmutableConfig $config */
    $config = $this->config('ila_attendee_registration_block.settings');

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message if user already register'),
      '#default_value' => t($config->get('message.register')),
      '#rows' => 6,
      '#description' => t('This is message will be displayed if user already registered in this conference'),
    ];

    $form['thanks_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Thank You Message'),
      '#default_value' => t($config->get('message.thanks')),
      '#rows' => 6,
      '#description' => t('This message will be shown when the attendee sends their data.'),
    ];

    $form['email_attendee'] = [
      '#type' => 'details',
      '#title' => $this->t('Email to attendee'),
    ];

    $form['email_attendee']['subject_attendee'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => t($config->get('message.email_attendee.subject')),
      '#maxlength' => 180,
    ];

    $form['email_attendee']['body_attendee'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Body text'),
      '#default_value' => t($config->get('message.email_attendee.body')),
      '#rows' => 6,
    ];

    $form['email_host'] = [
      '#type' => 'details',
      '#title' => $this->t('Email to host'),
    ];

    $form['email_host']['subject_host'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => t($config->get('message.email_host.subject')),
      '#maxlength' => 180,
    ];

    $form['email_host']['body_host'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Body text'),
      '#default_value' => t($config->get('message.email_host.body')),
      '#rows' => 6,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('ila_attendee_registration_block.settings')
      ->set('message.register', $form_state->getValue('message'))
      ->set('message.thanks', $form_state->getValue('thanks_message'))
      ->set('message.email_attendee.subject', $form_state->getValue('subject_attendee'))
      ->set('message.email_attendee.body', $form_state->getValue('body_attendee'))
      ->set('message.email_host.subject', $form_state->getValue('subject_host'))
      ->set('message.email_host.body', $form_state->getValue('body_host'))
      ->save();
  }
}
