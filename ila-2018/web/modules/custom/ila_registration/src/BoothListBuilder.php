<?php

namespace Drupal\ila_registration;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Booth entities.
 */
class BoothListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['uid'] = $this->t('Author');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\ila_registration\Entity\BoothInterface */
    $row['id']['data'] = $entity->id();
    $row['name']['data'] = $entity->toLink()->toRenderable();
    $row['uid']['data'] = $entity->getOwner()->toLink()->toRenderable();

    return $row + parent::buildRow($entity);
  }

}
