<?php

namespace Drupal\ila_back_to_request\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a block for decoupled submit button.
 *
 * @Block(
 *   id = "back_to_request",
 *   admin_label = @Translation("Back to Request page"),
 *   provider = "node",
 *   category = @Translation("Forms")
 * )
 */
class BackToRequestBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
  }

  /**
   * Implements \Drupal\block\BlockBase::build().
   */
  public function build() {
    $build = [];

    $current_url = Url::fromRoute('<current>')->toString();

    $link = Url::fromUserInput('/' . implode('/', array_slice(explode('/', $current_url), 0, -1)));
    $text = t('Back to exhibitor zone');
    $link_element = Link::fromTextAndUrl($text, $link);
    $build = $link_element->toRenderable();
    $build['#attributes']['class'] = [
      'print-button',
      'back-to-request',
    ];

    return $build;
  }

}
