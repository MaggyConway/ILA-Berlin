<?php

namespace Drupal\ila_facets_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a 'ILA Reset link Facets Block' block.
 *
 * @Block(
 *  id = "ila_reset_link_facets_block",
 *  admin_label = @Translation("ILA Reset link Facets Block"),
 * )
 */
class IlaResetLinkFacetsBlock extends BlockBase implements BlockPluginInterface {
  
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
    
    $current_uri = \Drupal::request()->getRequestUri();
    $text = t('Reset filters');

    if (!\Drupal::request()->query->all()) {
      return $build;
    }
    if (\Drupal::routeMatch()->getRouteName() == 'views.ajax') {
      $node_ids = $_SESSION["cc_node_ids"];
      foreach ($node_ids as $key_id => $id_value) {
        if (strpos($current_uri, '' . $key_id . '') !== FALSE) {
          $link = Url::fromUserInput('/node/'. $key_id);
          $link_element = Link::fromTextAndUrl($text, $link);
          $build = $link_element->toRenderable();
        }
      }
      if (empty ($build) && !preg_match('/node\/(\d+)/', $current_uri, $matches)) {
        preg_match_all('/(\/en\/?([a-zA-Z\_0-9\.-]+))/i', $current_uri, $matches);
        if (isset($matches[0]) && !empty($matches[0])) {
          foreach ($matches[0] as $match) {
            if ($match !== '/en/views') {
              $link = Url::fromUserInput($match);
              $link_element = Link::fromTextAndUrl($text, $link);
              $build = $link_element->toRenderable();
            }
          }
        }
      }
    }
    elseif (strpos($current_uri, '/node/') !== FALSE) {
      $node = \Drupal::routeMatch()->getParameter('node');
      if ($node instanceof \Drupal\node\NodeInterface) {
        if ($node->id()) {
          $link = Url::fromUserInput('/node/' . $node->id());
          $link_element = Link::fromTextAndUrl($text, $link);
          $build = $link_element->toRenderable();
        }
      }
    }
    elseif (!preg_match('/node\/(\d+)/', $current_uri, $matches)) {
      $parts = parse_url($current_uri);
      if (isset($parts['path']) && !empty($parts['path'])) {
        $link = Url::fromUserInput($parts['path']);
        $link_element = Link::fromTextAndUrl($text, $link);
        $build = $link_element->toRenderable();
      }
    }

    $build['#attributes']['class'] = [
      'reset-facet-filters',
    ];

    return $build;
  }
  
}
