<?php

/**
 * @file
 * Contains Drupal\ila_file_field_image_formatter\Plugin\Field\FieldFormatter\FileFieldImageFormatter.
 */

namespace Drupal\ila_file_field_image_formatter\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ila_file_field_image_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "ila_file_field_image_formatter",
 *   label = @Translation("File image formatter"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class FileFieldImageFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'image_formatter' => '',
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $image_styles = image_style_options(FALSE);
    $element['image_formatter'] = array(
      '#title' => t('Image style'),
      '#type' => 'select',
      '#default_value' => $this->getSetting('image_formatter'),
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
      '#description' => array(
        '#markup' => t('Configure Image Styles'),
      ),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();

    $image_styles = image_style_options(FALSE);
    // Unset possible 'No defined styles' option.
    unset($image_styles['']);
    // Styles could be lost because of enabled/disabled modules that defines
    // their styles in code.
    $image_style_setting = $this->getSetting('image_formatter');
    if (isset($image_styles[$image_style_setting])) {
      $summary[] = t('Image style: @style', array('@style' => $image_styles[$image_style_setting]));
    }
    else {
      $summary[] = t('Original image');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $image_style_setting = $this->getSetting('image_formatter');

    foreach ($items as $delta => $item) {
      $fid = $item->target_id;
      $file_entity = file_load($fid);
      $url = file_create_url($file_entity->getFileUri());
      $item_attributes = $item->_attributes;

      $elements[$delta] = array(
        '#theme' => 'image_formatter',
        '#item' => $item,
        '#item_attributes' => $item_attributes,
        '#image_style' => $image_style_setting,
        '#url' => $url,
      );
    }

    return $elements;
  }

//  /**
//   * Generate the output appropriate for one field item.
//   *
//   * @param \Drupal\Core\Field\FieldItemInterface $item
//   *   One field item.
//   *
//   * @return string
//   *   The textual output generated.
//   */
//  protected function viewValue(FieldItemInterface $item) {
//    // The text value has no text format assigned to it, so the user input
//    // should equal the output, including newlines.
//    return nl2br(SafeMarkup::checkPlain($item->value));
//  }

}
