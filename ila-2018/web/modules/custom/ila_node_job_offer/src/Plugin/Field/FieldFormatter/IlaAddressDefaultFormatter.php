<?php

namespace Drupal\ila_node_job_offer\Plugin\Field\FieldFormatter;

use CommerceGuys\Addressing\LocaleHelper;
use Drupal\Core\Render\Element;
use Drupal\address\Plugin\Field\FieldFormatter\AddressDefaultFormatter;

/**
 * Plugin implementation of the 'ila_address_default' formatter.
 *
 * @FieldFormatter(
 *   id = "ila_address_default",
 *   label = @Translation("ILA Default"),
 *   field_types = {
 *     "address",
 *   },
 * )
 */
class IlaAddressDefaultFormatter extends AddressDefaultFormatter {

  /**
   * Inserts the rendered elements into the format string.
   *
   * @param string $content
   *   The rendered element.
   * @param array $element
   *   An associative array containing the properties and children of the
   *   element.
   *
   * @return string
   *   The new rendered element.
   */
  public static function postRender($content, array $element) {
    /** @var \CommerceGuys\Addressing\AddressFormat\AddressFormat $address_format */
    $address_format = $element['address_format']['#value'];
    $locale = $element['locale']['#value'];
    // Add the country to the bottom or the top of the format string,
    // depending on whether the format is minor-to-major or major-to-minor.
    if (LocaleHelper::match($address_format->getLocale(), $locale)) {
      $format_string = '%country' . "\n" . $address_format->getLocalFormat();
    }
    else {
      $format_string = $address_format->getFormat() . "\n" . '%country';
    }

    $replacements = [];
    foreach (Element::getVisibleChildren($element) as $key) {
      $child = $element[$key];
      if (isset($child['#placeholder'])) {
        $replacements[$child['#placeholder']] = $child['#value'] ? $child['#markup'] : '';
      }
    }
    $content = self::replacePlaceholders($format_string, $replacements);
    //$content = nl2br($content, FALSE);

    return $content;
  }

}
