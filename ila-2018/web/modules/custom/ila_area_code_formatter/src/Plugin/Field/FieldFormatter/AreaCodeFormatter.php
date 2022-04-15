<?php

/**
 * @file
 * Contains \Drupal\ila_area_code_formatter\Plugin\Field\FieldFormatter\AreaCodeFormatter.
 */

namespace Drupal\ila_area_code_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ila_area_code_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "ila_area_code_formatter",
 *   label = @Translation("Formatted telephone"),
 *   field_types = {
 *     "telephone"
 *   }
 * )
 */
class AreaCodeFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();
    foreach ($items as $delta => $item) {
      $view = $item->view();
      $value = $view['#context']['value'];
      if (!$value[0]) {
        $value = substr($value, 1);
      }
      $elements[$delta] = array(
        '#markup' => $value,
      );
    }
    return $elements;
  }
}