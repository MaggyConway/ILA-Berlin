<?php

/**
 * @file
 * Contains ila_registration_company.page.inc.
 *
 * Page callback for Company entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Company templates.
 *
 * Default template: ila_registration_company.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_ila_registration_company(array &$variables) {
  // Fetch Company Entity Object.
  $ila_registration_company = $variables['elements']['#ila_registration_company'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
