<?php

namespace Drupal\ila_optional_link\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;


/**
 * Plugin implementation of the 'link' widget.
 *
 * @FieldWidget(
 *   id = "optional_link",
 *   label = @Translation("Optional link (with attributes)"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class OptionalLinkWidget extends LinkWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    if ($element['uri']['#default_value'] === 'route:<nolink>') {
      $element['uri']['#default_value'] = '';
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as &$value) {
      if ($value['uri'] === '') {
        $value['uri'] = 'route:<nolink>';
      }
      $value['uri'] = static::getUserEnteredStringAsUri($value['uri']);
      $value += ['options' => []];
    }
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public static function validateTitleNoLink(&$element, FormStateInterface $form_state, $form) {
    // Override default validation to avoid errors with empty URL.
  }

}
