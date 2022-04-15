<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Booth entity.
 *
 * @ingroup ila_registration
 *
 * @ContentEntityType(
 *   id = "ila_registration_booth",
 *   label = @Translation("Booth"),
 *   bundle_label = @Translation("Booth type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ila_registration\BoothListBuilder",
 *     "views_data" = "Drupal\ila_registration\Entity\BoothViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\ila_registration\Form\BoothForm",
 *       "add" = "Drupal\ila_registration\Form\BoothForm",
 *       "edit" = "Drupal\ila_registration\Form\BoothForm",
 *       "delete" = "Drupal\ila_registration\Form\BoothDeleteForm",
 *     },
 *     "access" = "Drupal\ila_registration\BoothAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\ila_registration\BoothHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "ila_registration_booth",
 *   admin_permission = "administer booth entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "uuid" = "uuid",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/registrations/booth/{ila_registration_booth}",
 *     "add-page" = "/admin/registrations/booth/add",
 *     "add-form" = "/admin/registrations/booth/add/{ila_registration_booth_type}",
 *     "edit-form" = "/admin/registrations/booth/{ila_registration_booth}/edit",
 *     "delete-form" = "/admin/registrations/booth/{ila_registration_booth}/delete",
 *     "collection" = "/admin/registrations/booth",
 *   },
 *   bundle_entity_type = "ila_registration_booth_type",
 *   field_ui_base_route = "entity.ila_registration_booth_type.edit_form"
 * )
 */
class Booth extends ContentEntityBase implements BoothInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'uid' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    if (!$this->bundle()) {
      return '';
    }

    return \Drupal::entityTypeManager()
      ->getStorage('ila_registration_booth_type')
      ->load($this->bundle())->label();
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Booth entity.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
