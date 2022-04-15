<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\user\UserInterface;

/**
 * Defines the Registration entity.
 *
 * @ContentEntityType(
 *   id = "ila_registration",
 *   label = @Translation("Registration"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ila_registration\RegistrationListBuilder",
 *     "views_data" = "Drupal\ila_registration\Entity\RegistrationViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\ila_registration\Form\RegistrationForm",
 *       "add" = "Drupal\ila_registration\Form\RegistrationForm",
 *       "edit" = "Drupal\ila_registration\Form\RegistrationForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "access" = "Drupal\ila_registration\RegistrationAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\ila_registration\RegistrationHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "ila_registration",
 *   admin_permission = "administer registration entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/registrations/{ila_registration}",
 *     "add-form" = "/admin/registrations/add",
 *     "edit-form" = "/admin/registrations/{ila_registration}/edit",
 *     "delete-form" = "/admin/registrations/{ila_registration}/delete",
 *     "collection" = "/admin/registrations",
 *   }
 * )
 */
class Registration extends ContentEntityBase implements RegistrationInterface {

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
    return !empty($this->id()) ? new TranslatableMarkup('Order #@number', ['@number' => $this->id()]) : '';
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
      ->setDescription(t('The user ID of author of the Registration entity.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['booths'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Booths'))
      ->setDescription(t('The list of booths to order.'))
      ->setSetting('target_type', 'ila_registration_booth')
      ->setSetting('handler', 'default')
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'inline_entity_form_complex',
        'weight' => 6,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'allow_new' => TRUE,
          'allow_existing' => FALSE,
        ],
      ]);

    $fields['company'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Company'))
      ->setDescription(t('Company to register'))
      ->setSetting('target_type', 'ila_registration_company')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 6,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
