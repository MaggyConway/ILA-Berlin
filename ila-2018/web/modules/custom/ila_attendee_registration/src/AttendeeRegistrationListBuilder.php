<?php

namespace Drupal\ila_attendee_registration;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Attendee registration entities.
 *
 * @ingroup ila_attendee_registration
 */
class AttendeeRegistrationListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Attendee registration ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\ila_attendee_registration\Entity\AttendeeRegistration */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.attendee_registration.edit_form',
      ['attendee_registration' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
