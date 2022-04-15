<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Exhibitor entities.
 *
 * @ingroup ila_registration
 */
interface ExhibitorInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Exhibitor name.
   *
   * @return string
   *   Name of the Exhibitor.
   */
  public function getName();

  /**
   * Sets the Exhibitor name.
   *
   * @param string $name
   *   The Exhibitor name.
   *
   * @return \Drupal\ila_registration\Entity\ExhibitorInterface
   *   The called Exhibitor entity.
   */
  public function setName($name);

  /**
   * Gets the Exhibitor creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Exhibitor.
   */
  public function getCreatedTime();

  /**
   * Sets the Exhibitor creation timestamp.
   *
   * @param int $timestamp
   *   The Exhibitor creation timestamp.
   *
   * @return \Drupal\ila_registration\Entity\ExhibitorInterface
   *   The called Exhibitor entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Exhibitor published status indicator.
   *
   * Unpublished Exhibitor are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Exhibitor is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Exhibitor.
   *
   * @param bool $published
   *   TRUE to set this Exhibitor to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\ila_registration\Entity\ExhibitorInterface
   *   The called Exhibitor entity.
   */
  public function setPublished($published);

}
