<?php

namespace Drupal\ila_attendee_registration\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Attendee registration entity.
 *
 * @ingroup ila_attendee_registration
 *
 * @ContentEntityType(
 *   id = "attendee_registration",
 *   label = @Translation("Attendee registration"),
 *   handlers = {
 *     "storage" = "Drupal\ila_attendee_registration\AttendeeRegistrationStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ila_attendee_registration\AttendeeRegistrationListBuilder",
 *     "views_data" = "Drupal\ila_attendee_registration\Entity\AttendeeRegistrationViewsData",
 *     "translation" = "Drupal\ila_attendee_registration\AttendeeRegistrationTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\ila_attendee_registration\Form\AttendeeRegistrationForm",
 *       "add" = "Drupal\ila_attendee_registration\Form\AttendeeRegistrationForm",
 *       "edit" = "Drupal\ila_attendee_registration\Form\AttendeeRegistrationForm",
 *       "delete" = "Drupal\ila_attendee_registration\Form\AttendeeRegistrationDeleteForm",
 *     },
 *     "access" = "Drupal\ila_attendee_registration\AttendeeRegistrationAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\ila_attendee_registration\AttendeeRegistrationHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "attendee_registration",
 *   data_table = "attendee_registration_field_data",
 *   revision_table = "attendee_registration_revision",
 *   revision_data_table = "attendee_registration_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer attendee registration entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/attendee_registration/{attendee_registration}",
 *     "add-form" = "/admin/content/attendee_registration/add",
 *     "edit-form" = "/admin/content/attendee_registration/{attendee_registration}/edit",
 *     "delete-form" = "/admin/content/attendee_registration/{attendee_registration}/delete",
 *     "version-history" = "/admin/content/attendee_registration/{attendee_registration}/revisions",
 *     "revision" = "/admin/content/attendee_registration/{attendee_registration}/revisions/{attendee_registration_revision}/view",
 *     "revision_revert" = "/admin/content/attendee_registration/{attendee_registration}/revisions/{attendee_registration_revision}/revert",
 *     "revision_delete" = "/admin/content/attendee_registration/{attendee_registration}/revisions/{attendee_registration_revision}/delete",
 *     "translation_revert" = "/admin/content/attendee_registration/{attendee_registration}/revisions/{attendee_registration_revision}/revert/{langcode}",
 *     "collection" = "/admin/content/attendee_registration",
 *   },
 *   field_ui_base_route = "attendee_registration.settings"
 * )
 */
class AttendeeRegistration extends RevisionableContentEntityBase implements AttendeeRegistrationInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the attendee_registration owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
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
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Attendee registration entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Attendee registration entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Attendee registration is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
