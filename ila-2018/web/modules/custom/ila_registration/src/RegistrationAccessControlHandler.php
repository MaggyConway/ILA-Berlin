<?php

namespace Drupal\ila_registration;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Registration entity.
 *
 * @see \Drupal\ila_registration\Entity\Registration.
 */
class RegistrationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ila_registration\Entity\RegistrationInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIf($account->hasPermission('view all registration entities')
          || ($account->hasPermission('view own registration entities') && $entity->getOwnerId() == $account->id()));

      case 'update':
        return AccessResult::allowedIf($account->hasPermission('edit all registration entities')
          || ($account->hasPermission('edit own registration entities') && $entity->getOwnerId() == $account->id()));

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('delete all registration entities')
          || ($account->hasPermission('delete own registration entities') && $entity->getOwnerId() == $account->id()));
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add registration entities');
  }

}
