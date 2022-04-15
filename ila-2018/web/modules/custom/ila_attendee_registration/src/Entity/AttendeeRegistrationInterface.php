<?php

namespace Drupal\ila_attendee_registration\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Attendee registration entities.
 *
 * @ingroup ila_attendee_registration
 */
interface AttendeeRegistrationInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Attendee registration name.
   *
   * @return string
   *   Name of the Attendee registration.
   */
  public function getName();

  /**
   * Sets the Attendee registration name.
   *
   * @param string $name
   *   The Attendee registration name.
   *
   * @return \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface
   *   The called Attendee registration entity.
   */
  public function setName($name);

  /**
   * Gets the Attendee registration creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Attendee registration.
   */
  public function getCreatedTime();

  /**
   * Sets the Attendee registration creation timestamp.
   *
   * @param int $timestamp
   *   The Attendee registration creation timestamp.
   *
   * @return \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface
   *   The called Attendee registration entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Attendee registration published status indicator.
   *
   * Unpublished Attendee registration are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Attendee registration is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Attendee registration.
   *
   * @param bool $published
   *   TRUE to set this Attendee registration to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface
   *   The called Attendee registration entity.
   */
  public function setPublished($published);

  /**
   * Gets the Attendee registration revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Attendee registration revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface
   *   The called Attendee registration entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Attendee registration revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Attendee registration revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface
   *   The called Attendee registration entity.
   */
  public function setRevisionUserId($uid);

}
