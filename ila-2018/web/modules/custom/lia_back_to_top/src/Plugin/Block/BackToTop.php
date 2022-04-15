<?php

namespace Drupal\lia_back_to_top\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Add "Back to top" link.
 *
 * @Block(
 *   id = "lia_back_to_top",
 *   admin_label = @Translation("Back to top link"),
 * )
 */
class BackToTop extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['back-to-top'],
      ],
      'link' => [
        '#type' => 'link',
        '#title' => $this->t('up'),
        '#url' => Url::fromUri('internal:'),
        '#attributes' => [
          'class' => ['back-to-top__link'],
        ],
      ],
      '#attached' => [
        'library' => ['lia_back_to_top/back_to_top'],
      ],
    ];
  }
}
