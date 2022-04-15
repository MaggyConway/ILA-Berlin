<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Exhibitor type entity.
 *
 * @ConfigEntityType(
 *   id = "ila_registration_exhibitor_type",
 *   label = @Translation("Exhibitor type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ila_registration\ExhibitorTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ila_registration\Form\ExhibitorTypeForm",
 *       "edit" = "Drupal\ila_registration\Form\ExhibitorTypeForm",
 *       "delete" = "Drupal\ila_registration\Form\ExhibitorTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ila_registration\ExhibitorTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "ila_registration_exhibitor_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "ila_registration_exhibitor",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/ila_registration_exhibitor_type/{ila_registration_exhibitor_type}",
 *     "add-form" = "/admin/structure/ila_registration_exhibitor_type/add",
 *     "edit-form" = "/admin/structure/ila_registration_exhibitor_type/{ila_registration_exhibitor_type}/edit",
 *     "delete-form" = "/admin/structure/ila_registration_exhibitor_type/{ila_registration_exhibitor_type}/delete",
 *     "collection" = "/admin/structure/ila_registration_exhibitor_type"
 *   }
 * )
 */
class ExhibitorType extends ConfigEntityBundleBase implements ExhibitorTypeInterface {

  /**
   * The Exhibitor type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Exhibitor type label.
   *
   * @var string
   */
  protected $label;

}
