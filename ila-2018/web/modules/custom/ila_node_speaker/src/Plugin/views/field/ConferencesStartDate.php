<?php
/**
 * ConferencesStartDate
 */

namespace Drupal\ila_node_speaker\Plugin\views\field;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * @ingroup views_field_handlers
 *
 * @ViewsField("ila_node_speaker_start_date")
 */
class ConferencesStartDate extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // We don't need to modify query for this particular example.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['conferences_start_date'] = ['default' => 'l, j. F Y'];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['conferences_start_date'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Conferences start date format'),
      '#description' => $this->t('This format will be used for start date.'),
      '#default_value' => $this->options['conferences_start_date'],
    ];
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $entity = $values->_entity;
    if ($date_and_time = $entity->get('field_date_and_time')->getValue()) {
      $start_date_time = new DrupalDateTime($date_and_time[0]['value']);
      $start_date_time = $start_date_time->format($this->options['conferences_start_date']);

      return [
        '#markup' => $start_date_time,
      ];
    }

    return FALSE;
  }
}
