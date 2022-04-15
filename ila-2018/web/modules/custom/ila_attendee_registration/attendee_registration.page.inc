<?php

/**
 * @file
 * Contains attendee_registration.page.inc.
 *
 * Page callback for Attendee registration entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Attendee registration templates.
 *
 * Default template: attendee_registration.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_attendee_registration(array &$variables) {
  // Fetch AttendeeRegistration Entity Object.
  $attendee_registration = $variables['elements']['#attendee_registration'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
