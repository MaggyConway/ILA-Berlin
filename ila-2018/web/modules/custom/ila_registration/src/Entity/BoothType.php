<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Booth type entity.
 *
 * @ConfigEntityType(
 *   id = "ila_registration_booth_type",
 *   label = @Translation("Booth type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ila_registration\BoothTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ila_registration\Form\BoothTypeForm",
 *       "edit" = "Drupal\ila_registration\Form\BoothTypeForm",
 *       "delete" = "Drupal\ila_registration\Form\BoothTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ila_registration\BoothTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "ila_registration_booth_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "ila_registration_booth",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/ila_registration_booth_type/{ila_registration_booth_type}",
 *     "add-form" = "/admin/structure/ila_registration_booth_type/add",
 *     "edit-form" = "/admin/structure/ila_registration_booth_type/{ila_registration_booth_type}/edit",
 *     "delete-form" = "/admin/structure/ila_registration_booth_type/{ila_registration_booth_type}/delete",
 *     "collection" = "/admin/structure/ila_registration_booth_type"
 *   }
 * )
 */
class BoothType extends ConfigEntityBundleBase implements BoothTypeInterface {

  /**
   * The Booth type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Booth type label.
   *
   * @var string
   */
  protected $label;

}
