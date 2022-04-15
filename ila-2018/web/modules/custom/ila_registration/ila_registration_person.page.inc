<?php

/**
 * @file
 * Contains ila_registration_person.page.inc.
 *
 * Page callback for Person entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Person templates.
 *
 * Default template: ila_registration_person.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_ila_registration_person(array &$variables) {
  // Fetch Person Entity Object.
  $ila_registration_person = $variables['elements']['#ila_registration_person'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
