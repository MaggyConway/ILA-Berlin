<?php

namespace Drupal\ila_attendee_registration;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class AttendeeRegistrationStorage extends SqlContentEntityStorage implements AttendeeRegistrationStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(AttendeeRegistrationInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {attendee_registration_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {attendee_registration_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(AttendeeRegistrationInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {attendee_registration_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('attendee_registration_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
