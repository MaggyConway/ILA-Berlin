<?php

namespace Drupal\ila_attendee_registration;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Attendee registration entity.
 *
 * @see \Drupal\ila_attendee_registration\Entity\AttendeeRegistration.
 */
class AttendeeRegistrationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished attendee registration entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published attendee registration entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit attendee registration entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete attendee registration entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add attendee registration entities');
  }

}
