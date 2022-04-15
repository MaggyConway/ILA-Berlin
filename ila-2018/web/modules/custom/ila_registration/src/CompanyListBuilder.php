<?php

namespace Drupal\ila_registration;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Company entities.
 *
 * @ingroup ila_registration
 */
class CompanyListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Company ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\ila_registration\Entity\Company */
    $row['id']['data'] = $entity->id();
    $row['name']['data'] = $entity->toLink()->toRenderable();
    return $row + parent::buildRow($entity);
  }

}
