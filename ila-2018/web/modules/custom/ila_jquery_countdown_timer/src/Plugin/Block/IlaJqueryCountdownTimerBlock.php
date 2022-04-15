<?php
/**
 * @file
 * Contains \Drupal\ila_jquery_countdown_timer\Plugin\Block\IlaJqueryCountdownTimerBlock.
 */

namespace Drupal\ila_jquery_countdown_timer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use \DateTime;

/**
 * Provides a "Jquery Countdown Timer" block.
 *
 * @Block(
 *   id = "ila_jquery_countdown_timer",
 *   admin_label = @Translation("ILA Countdown Timer")
 * )
 */
class IlaJqueryCountdownTimerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $dt = new DateTime('tomorrow');
    $countdown_datetime = $dt->format('Y-m-d H:i:s');

    return array('countdown_datetime' => $countdown_datetime);
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['jquery_countdown_timer_date'] = array(
      '#type' => 'datetime',
      '#title' => $this->t('Timer date'),
      '#required' => 1,
      '#default_value' => new DrupalDateTime($this->configuration['countdown_datetime']),
      '#date_date_element' => 'date',
      '#date_time_element' => 'time',
      '#date_year_range' => '2016:+50',
    );
    $form['jquery_countdown_timer_block_text'] = array(
      '#type' => 'text_format',
      '#title' => t('Block text'),
      '#format' => $this->configuration['block_text']['format'],
      '#default_value' => $this->t($this->configuration['block_text']['value']),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $dt = $form_state->getValue('jquery_countdown_timer_date');
    $this->configuration['countdown_datetime'] = $dt->format('Y-m-d H:i:s');
    $this->configuration['block_text'] = $form_state->getValue('jquery_countdown_timer_block_text');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $settings = [
      'unixtimestamp' => strtotime($this->configuration['countdown_datetime']),
    ];
    $block_text = $this->t($this->configuration['block_text']['value']);

    $build = [];
    $build['content'] = [
      'image' => [
        '#type' => 'container',
        '#attributes' => [
          'id' => 'lottie',
        ],
      ],
      'block-content' => [
        '#type' => 'container',
        'text' => [
          '#markup' => $block_text
        ],
        'timer' => [
          '#type' => 'container',
          '#attributes' => [
            'id' => 'jquery-countdown-timer',
          ],
        ],
        '#attributes' => [
          'class' => ['countdown-timer-content'],
        ],
      ],
    ];

    // Attach library containing css and js files.
    $build['#attached']['library'][] = 'ila_jquery_countdown_timer/countdown.timer';
    $build['#attached']['drupalSettings']['countdown'] = $settings;

    return $build;
  }
}
