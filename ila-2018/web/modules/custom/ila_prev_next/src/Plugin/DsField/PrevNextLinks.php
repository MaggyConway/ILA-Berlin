<?php

namespace Drupal\ila_prev_next\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * Plugin that renders the prev-next links based on publication date.
 *
 * @DsField(
 *   id = "ila_prev_next",
 *   title = @Translation("Prev-Next links"),
 *   entity_type = "node",
 *   provider = "ila_prev_next"
 * )
 */
class PrevNextLinks extends DsFieldBase {

  const OPERATOR_MORE = '>';
  const OPERATOR_LESS = '<';

  public function build() {
    $build = [];

    if ($prev_node = $this->findPrevNode()) {
      $build['prev'] = $prev_node->toLink($this->t('Previous Article'))->toRenderable();
      $build['prev']['#attributes']['class'][] = 'prev';
    }

    if ($next_node = $this->findNextNode()) {
      $build['next'] = $next_node->toLink($this->t('Next Article'))->toRenderable();
      $build['next']['#attributes']['class'][] = 'next';
    }

    return $build;
  }

  private function findPrevNode() {
    return $this->findNode(self::OPERATOR_LESS);
  }

  public function findNextNode() {
    return $this->findNode(self::OPERATOR_MORE);
  }

  private function findNode($operator = '<') {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $this->entity();

    $storage = \Drupal::entityTypeManager()->getStorage('node');

    $query = $storage->getQuery();

    $query
      ->range(0, 1)
      ->condition('type', $node->bundle())
      ->condition('created', $node->getCreatedTime(), $operator);

    $direction = ($operator == self::OPERATOR_LESS) ? 'DESC' : 'ASC';
    $query->sort('created', $direction);

    $result = $storage->loadMultiple($query->execute());

    return $result ? reset($result) : $result;
  }

}