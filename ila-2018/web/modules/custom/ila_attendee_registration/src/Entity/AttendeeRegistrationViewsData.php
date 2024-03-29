<?php

namespace Drupal\ila_attendee_registration\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Attendee registration entities.
 */
class AttendeeRegistrationViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
