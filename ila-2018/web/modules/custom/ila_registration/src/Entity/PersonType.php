<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Person type entity.
 *
 * @ConfigEntityType(
 *   id = "ila_registration_person_type",
 *   label = @Translation("Person type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ila_registration\PersonTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ila_registration\Form\PersonTypeForm",
 *       "edit" = "Drupal\ila_registration\Form\PersonTypeForm",
 *       "delete" = "Drupal\ila_registration\Form\PersonTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ila_registration\PersonTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "ila_registration_person_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "ila_registration_person",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/ila_registration_person_type/{ila_registration_person_type}",
 *     "add-form" = "/admin/structure/ila_registration_person_type/add",
 *     "edit-form" = "/admin/structure/ila_registration_person_type/{ila_registration_person_type}/edit",
 *     "delete-form" = "/admin/structure/ila_registration_person_type/{ila_registration_person_type}/delete",
 *     "collection" = "/admin/structure/ila_registration_person_type"
 *   }
 * )
 */
class PersonType extends ConfigEntityBundleBase implements PersonTypeInterface {

  /**
   * The Person type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Person type label.
   *
   * @var string
   */
  protected $label;

}
