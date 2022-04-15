<?php

namespace Drupal\ila_registration;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * List builder handler for Registration entity type.
 */
class RegistrationListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header = [
      'label' => $this->t('Label'),
      'uid' => $this->t('Author'),
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\ila_registration\Entity\RegistrationInterface $entity */
    $row['label']['data'] = $entity->toLink()->toRenderable();
    $row['uid']['data'] = $entity->getOwner()->toLink()->toRenderable();
    return $row + parent::buildRow($entity);
  }

}
