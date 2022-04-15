<?php

namespace Drupal\ila_facets_block\Plugin\Block;

use Drupal\ila_facets_block\Plugin\Block\IlaFacetsBlock;

/**
 * Provides a 'ILA Facets Block Conferences' block.
 *
 * @Block(
 *  id = "ila_facets_block_conferences",
 *  admin_label = @Translation("ILA Facets Block Conferences"),
 * )
 */
class IlaFacetsBlockConferences extends IlaFacetsBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $show_title = !isset($this->configuration['show_title']) ? FALSE : $this->configuration['show_title'];
    //$facets_to_include = !isset($this->configuration['facets_to_include']) ? [] : $this->configuration['facets_to_include'];
    
    $facets_to_include = [
      'facet_block:conference_start_date_and_time' => 'facet_block:conference_start_date_and_time',
      'facet_block:conference_category' => 'facet_block:conference_category',
      'facet_block:speakers' => 'facet_block:speakers',
    ];

    $build = [
      '#theme' => 'ila_facets_block',
      '#show_title' => $show_title,
      '#facets' => $this->buildFacets($facets_to_include),
    ];
    
    return $build;
  }

}
