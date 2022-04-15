<?php

namespace Drupal\ila_grouped_options\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\OptionsWidgetBase;
use Drupal\Core\Render\Element;
use Drupal\taxonomy\TermStorage;

/**
 * Plugin implementation of the 'grouped_options' widget.
 *
 * @FieldWidget(
 *   id = "grouped_options",
 *   label = @Translation("Grouped options"),
 *   field_types = {
 *     "entity_reference",
 *   },
 *   multiple_values = TRUE
 * )
 */
class GroupedOptionsWidget extends OptionsWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $options = $this->getOptions($items->getEntity());
    $selected = $this->getSelectedOptions($items);

    // If required and there is one single option, preselect it.
    if ($this->required && count($options) == 1) {
      reset($options);
      $selected = [key($options)];
    }

    if ($this->multiple) {
      $element += [
        '#type' => 'checkboxes',
        '#default_value' => $selected,
        '#options' => $options,
      ];
    }
    else {
      $element += [
        '#type' => 'radios',
        // Radio buttons need a scalar value. Take the first default value, or
        // default to NULL so that the form element is properly recognized as
        // not having a default value.
        '#default_value' => $selected ? reset($selected) : NULL,
        '#options' => $options,
      ];
    }

    if (!isset($element['#pre_render'])) {
      $element['#pre_render'] = [];
    }
    $element['#pre_render'][] = [__CLASS__, 'preRender'];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEmptyLabel() {
    if (!$this->required && !$this->multiple) {
      return t('N/A');
    }
  }

  /**
   * Helper function: checks if taxonomy term with given ID has children.
   */
  private static function termHasChildren(string $term_id, TermStorage $term_storage = NULL) {
    if ($term_storage === NULL) {
      $term_storage = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term');
    }

    return !empty($term_storage->loadChildren($term_id));
  }

  /**
   * Helper function: returns list of all taxonomy terms from given vocabulary.
   */
  private static function getVocabularyTree(string $vid, TermStorage $term_storage = NULL) {
    if ($term_storage === NULL) {
      $term_storage = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term');
    }

    $terms_list = $term_storage->loadTree($vid);

    // Reorganize the list so it is possible to search by taxonomy term ID.
    $output = [];
    foreach ($terms_list as $key => $item) {
      $output[$item->tid] = $item;
    }

    return $output;
  }

  /**
   * Pre-render callback. Disables all checkboxes that have children.
   */
  public static function preRender(array $element) {
    $term_storage = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term');
    $terms_list = [];

    $element_children = Element::children($element, TRUE);
    foreach ($element_children as $key) {
      if (!isset($terms_list[$key])) {
        $terms_list = self::getVocabularyTree($term_storage->load($key)->getVocabularyId());
      }
      $term_depth = $terms_list[$key]->depth;
      $item_title = substr($element[$key]['#title'], $term_depth);

      $checkbox_element = &$element[$key];
      $wrapper_classes = 'grouped-checkbox-wrapper grouped-checkbox-depth-' . $term_depth;
      if (self::termHasChildren($key, $term_storage)) {
        $checkbox_element = [
          '#type' => 'item',
        ];
        $wrapper_classes .= ' grouped-checkbox-title-only';
      }

      // I'm so sorry...
      $checkbox_element['#prefix'] = '<div class="' . $wrapper_classes . '">';
      $checkbox_element['#suffix'] = '</div>';

      $checkbox_element['#title'] = $item_title;
    }

    return $element;
  }

}
