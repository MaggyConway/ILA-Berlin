<?php

namespace Drupal\ila_registration;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Booth entity.
 *
 * @see \Drupal\ila_registration\Entity\Booth.
 */
class BoothAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ila_registration\Entity\BoothInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIf($account->hasPermission('view all booth entities')
          || ($account->hasPermission('view own booth entities') && $entity->getOwnerId() == $account->id()));

      case 'update':
        return AccessResult::allowedIf($account->hasPermission('edit all booth entities')
          || ($account->hasPermission('edit own booth entities') && $entity->getOwnerId() == $account->id()));

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('delete all booth entities')
          || ($account->hasPermission('delete own booth entities') && $entity->getOwnerId() == $account->id()));
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add booth entities');
  }

}
