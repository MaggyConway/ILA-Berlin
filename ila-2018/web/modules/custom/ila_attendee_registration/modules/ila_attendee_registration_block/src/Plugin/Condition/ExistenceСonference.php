<?php

namespace Drupal\ila_attendee_registration_block\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Сonference' existence.
 *
 * @Condition(
 *   id = "existence_conference",
 *   label = @Translation("Existence Сonference"),
 * )
 */
class ExistenceСonference extends ConditionPluginBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   *
   * Check for the existence of the conference. If not,
   * deny access to the page.
   */
  public function evaluate() {
    if (!$node = $this->getConferenceNode()) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    if ($this->isNegated()) {
      return $this->t('Check for the existence of the conference');
    }

    return $this->t('Check for the existence of the conference');
  }

  /**
   * {@inheritdoc}
   */
  private function conferenceId() {
    $conference_id = '';
    $uri_segments = explode('/', \Drupal::service('path.current')->getPath());

    if (intval($uri_segments[2]) && intval($uri_segments[2]) != 0) {
      $conference_id = intval($uri_segments[2]);
    }

    return $conference_id;
  }

  /**
   * Returns the latest Main Exhibitor node object for the given user.
   */
  protected function getConferenceNode() {
    $values = [
      'nid' => $this->conferenceId(),
      'type' => 'conference',
    ];

    $node = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties($values);

    return end($node);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = parent::getCacheContexts();

    return $contexts;
  }

}
