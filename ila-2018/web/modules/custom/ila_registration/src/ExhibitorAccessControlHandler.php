<?php

namespace Drupal\ila_registration;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Exhibitor entity.
 *
 * @see \Drupal\ila_registration\Entity\Exhibitor.
 */
class ExhibitorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ila_registration\Entity\ExhibitorInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished exhibitor entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published exhibitor entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit exhibitor entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete exhibitor entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add exhibitor entities');
  }

}
