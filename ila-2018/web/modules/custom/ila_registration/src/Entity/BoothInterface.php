<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Booth entities.
 *
 * @ingroup ila_registration
 */
interface BoothInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Booth creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Booth.
   */
  public function getCreatedTime();

  /**
   * Sets the Booth creation timestamp.
   *
   * @param int $timestamp
   *   The Booth creation timestamp.
   *
   * @return \Drupal\ila_registration\Entity\BoothInterface
   *   The called Booth entity.
   */
  public function setCreatedTime($timestamp);

}
