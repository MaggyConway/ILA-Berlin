<?php

namespace Drupal\ila_facets_block\Plugin\facets\widget;

use Drupal\facets\FacetInterface;
use Drupal\facets\Plugin\facets\widget\LinksWidget;

/**
 * The checkbox / radios widget.
 *
 * @FacetsWidget(
 *   id = "ila_checkbox",
 *   label = @Translation("ILA List of checkboxes (Custom)"),
 *   description = @Translation("A configurable widget that shows a list of checkboxes"),
 * )
 */
class IlaCheckboxWidget extends LinksWidget {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    $build['#attributes']['class'][] = 'js-facets-checkbox-links';
    $build['#attached']['library'][] = 'ila_facets_block/ila_facets_block.facets.ila-checkbox-widget';
    return $build;
  }

}
