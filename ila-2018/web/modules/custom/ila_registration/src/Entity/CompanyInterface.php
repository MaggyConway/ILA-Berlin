<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Company entities.
 */
interface CompanyInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Company name.
   *
   * @return string
   *   Name of the Company.
   */
  public function getName();

  /**
   * Sets the Company name.
   *
   * @param string $name
   *   The Company name.
   *
   * @return \Drupal\ila_registration\Entity\CompanyInterface
   *   The called Company entity.
   */
  public function setName($name);

  /**
   * Gets the Company creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Company.
   */
  public function getCreatedTime();

  /**
   * Sets the Company creation timestamp.
   *
   * @param int $timestamp
   *   The Company creation timestamp.
   *
   * @return \Drupal\ila_registration\Entity\CompanyInterface
   *   The called Company entity.
   */
  public function setCreatedTime($timestamp);

}
