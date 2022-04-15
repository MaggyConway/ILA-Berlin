<?php

namespace Drupal\ila_registration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Company type entity.
 *
 * @ConfigEntityType(
 *   id = "ila_registration_company_type",
 *   label = @Translation("Company type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ila_registration\CompanyTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ila_registration\Form\CompanyTypeForm",
 *       "edit" = "Drupal\ila_registration\Form\CompanyTypeForm",
 *       "delete" = "Drupal\ila_registration\Form\CompanyTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ila_registration\CompanyTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "ila_registration_company_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "ila_registration_company",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/ila_registration_company_type/{ila_registration_company_type}",
 *     "add-form" = "/admin/structure/ila_registration_company_type/add",
 *     "edit-form" = "/admin/structure/ila_registration_company_type/{ila_registration_company_type}/edit",
 *     "delete-form" = "/admin/structure/ila_registration_company_type/{ila_registration_company_type}/delete",
 *     "collection" = "/admin/structure/ila_registration_company_type"
 *   }
 * )
 */
class CompanyType extends ConfigEntityBundleBase implements CompanyTypeInterface {

  /**
   * The Company type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Company type label.
   *
   * @var string
   */
  protected $label;

}
