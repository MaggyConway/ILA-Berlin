<?php

/**
 * @file
 * Contains ila_registration_booth.page.inc.
 *
 * Page callback for Booth entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Booth templates.
 *
 * Default template: ila_registration_booth.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_ila_registration_booth(array &$variables) {
  // Fetch Booth Entity Object.
  $ila_registration_booth = $variables['elements']['#ila_registration_booth'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
