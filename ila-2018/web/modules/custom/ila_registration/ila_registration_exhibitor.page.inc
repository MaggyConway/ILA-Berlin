<?php

/**
 * @file
 * Contains ila_registration_exhibitor.page.inc.
 *
 * Page callback for Exhibitor entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Exhibitor templates.
 *
 * Default template: ila_registration_exhibitor.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_ila_registration_exhibitor(array &$variables) {
  // Fetch Exhibitor Entity Object.
  $ila_registration_exhibitor = $variables['elements']['#ila_registration_exhibitor'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
