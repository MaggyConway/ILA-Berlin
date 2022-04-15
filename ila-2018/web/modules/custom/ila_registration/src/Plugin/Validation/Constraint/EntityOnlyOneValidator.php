<?php

namespace Drupal\ila_registration\Plugin\Validation\Constraint;

use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the IlaEntityOnlyOne constraint.
 */
class EntityOnlyOneValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($entity, Constraint $constraint) {
    if (isset($entity)) {
      /** @var \Drupal\Core\Entity\EntityInterface $entity */
      if ($this->entityIsNotOne($entity)) {
        $this->context->addViolation('You can create only one @type.', ['@type' => $entity->getEntityType()->getLabel()]);
      }
    }
  }

  /**
   * Check if another entity is exist for current user.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Entity object to check.
   *
   * @return bool
   *   TRUE if any another entity of same type is exist.
   */
  private function entityIsNotOne(EntityInterface $entity) {
    /** @var \Drupal\user\EntityOwnerInterface $entity */
    $entity_type = $entity->getEntityType();
    $author_key = $entity_type->getKey('uid');
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type->id());
    $existing_entities = $storage->loadByProperties([$author_key => $entity->getOwnerId()]);
    return (bool) $existing_entities;
  }

}
