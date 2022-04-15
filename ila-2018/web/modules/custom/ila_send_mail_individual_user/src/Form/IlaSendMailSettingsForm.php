<?php

namespace Drupal\ila_send_mail_individual_user\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Path\PathValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Email confirmer settings form.
 */
class IlaSendMailSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ila_send_mail_individual_user_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'ila_send_mail_individual_user.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    /** @var \Drupal\Core\Config\ImmutableConfig $config */
    $config = $this->config('ila_send_mail_individual_user.settings');

    $form['message_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $this->t($config->get('message.subject')),
      '#maxlength' => 180,
    ];

    $form['message_body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Body text'),
      '#default_value' => $this->t($config->get('message.body')),
      '#rows' => 6,
      '#description' => t('@title and @link params which will be replaced with node title and edit link accordingly. Do not delete them!'),
    ];

    $form['disabled_fields'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Disable fields'),
      '#options' => $this->getOptions(),
      '#description' => t('Chosen fields will be disabled for user with conference role.'),
      '#default_value' => $config->get('disabled_fields'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('ila_send_mail_individual_user.settings')
      ->set('message.subject', $this->t($form_state->getValue('message_subject')))
      ->set('message.body', $this->t($form_state->getValue('message_body')))
      ->set('disabled_fields', array_filter($form_state->getValues()['disabled_fields']))
      ->save();
  }

  /**
   * Returns a list with all fields for node conference.
   *
   * @return $fields[]
   *
   */
  protected function getOptions() {
    $fields = array();

    $entity_type_id = 'node';
    $bundle = 'conference';
    foreach (\Drupal::service('entity_field.manager')->getFieldDefinitions($entity_type_id, $bundle) as $field_name => $field_definition) {
      if (!empty($field_definition->getTargetBundle())) {
        $fields[$field_name] = $field_definition->getLabel();
      }
    }
    // exception
    $fields = [
        'title' => t('Title'),
        'langcode' => t('Language'),
      ] + $fields;

    return $fields;
  }

}
