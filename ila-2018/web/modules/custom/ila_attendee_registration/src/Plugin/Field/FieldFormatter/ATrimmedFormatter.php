<?php

namespace Drupal\ila_attendee_registration\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Custom field formatter, that trims <a> tags in content.
 *
 * @FieldFormatter(
 *   id = "a_trimmed_formatter",
 *   label = @Translation("ATrimmedFormatter"),
 *   field_types = {
 *     "text_long"
 *   }
 * )
 */
class ATrimmedFormatter extends FormatterBase {

  /**
   * @inheritDoc
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = ['#markup' => strip_tags($item->value, '<p><h2><h3><h4><h5><h6><em><strong><ul><ol><li><img><blockquote>')];
    }

    return $element;
  }
}
