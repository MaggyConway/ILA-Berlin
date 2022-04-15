<?php

namespace Drupal\ila_decoupled_submit\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a block for decoupled submit button.
 *
 * @Block(
 *   id = "decoupled_submit_button",
 *   admin_label = @Translation("Decoupled submit button"),
 *   provider = "node",
 *   category = @Translation("Forms")
 * )
 */
class DecoupledSubmitBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('ila_decoupled_submit.settings');

    return array(
      'form_selector' => $default_config->get('decoupled_submit_block.form_selector'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['form_selector'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Form selector'),
      '#description' => $this->t('Enter the CSS selector for the form element where the submit button should be decoupled from'),
      '#default_value' => isset($config['form_selector']) ? $config['form_selector'] : '',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();
    $this->configuration['form_selector'] = $values['form_selector'];
  }

  /**
   * Implements \Drupal\block\BlockBase::build().
   */
  public function build() {
    $build = [];

    $build['#markup'] = '<div class="decoupled-submit" id="decoupled-submit-target"></div>';
    $build['#attached']['library'] = 'ila_decoupled_submit/decoupled_submit';
    $build['#attached']['drupalSettings'] = [
      'decoupledSubmit' => [
        'form_selector' => $this->configuration['form_selector'],
      ],
    ];

    return $build;
  }

}
