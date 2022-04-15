<?php

namespace Drupal\ila_attendee_registration;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface;

/**
 * Defines the storage handler class for Attendee registration entities.
 *
 * This extends the base storage class, adding required special handling for
 * Attendee registration entities.
 *
 * @ingroup ila_attendee_registration
 */
interface AttendeeRegistrationStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Attendee registration revision IDs for a specific Attendee registration.
   *
   * @param \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface $entity
   *   The Attendee registration entity.
   *
   * @return int[]
   *   Attendee registration revision IDs (in ascending order).
   */
  public function revisionIds(AttendeeRegistrationInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Attendee registration author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Attendee registration revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface $entity
   *   The Attendee registration entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(AttendeeRegistrationInterface $entity);

  /**
   * Unsets the language for all Attendee registration with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
