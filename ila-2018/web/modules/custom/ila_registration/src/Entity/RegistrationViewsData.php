<?php

namespace Drupal\ila_registration\Entity;

use Drupal\views\EntityViewsData;

/**
 * Views data handler for Registration entity type.
 */
class RegistrationViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    /*
     * Avoid of weird core handler behavior.
     * Property "booths" doesn't exist in the field table.
     */
    $data['ila_registration__booths']['booths_target_id'] = $data['ila_registration__booths']['booths'];
    unset($data['ila_registration__booths']['booths']);

    return $data;
  }

}
