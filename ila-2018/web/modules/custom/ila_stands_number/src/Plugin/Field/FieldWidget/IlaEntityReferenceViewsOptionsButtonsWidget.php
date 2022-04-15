<?php

namespace Drupal\ila_stands_number\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\OptionsWidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\views\ViewExecutableFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\entity_reference_views_select\Plugin\Field\FieldWidget\EntityReferenceViewsOptionsButtonsWidget;

/**
 * Plugin implementation of the 'erviews_options_buttons' widget.
 *
 * @FieldWidget(
 *   id = "ila_erviews_options_buttons",
 *   label = @Translation("Ila Entity Reference Views Check boxes/radio buttons"),
 *   field_types = {
 *     "entity_reference",
 *   },
 *   multiple_values = TRUE
 * )
 */
class IlaEntityReferenceViewsOptionsButtonsWidget extends EntityReferenceViewsOptionsButtonsWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $options = $this->getOptions($items->getEntity());
    $selected = $this->getSelectedOptions($items);
    if ($this->getFieldSettings()['handler'] == 'views') {
      $view = $this->view_factory->get($this->view_loader->load($this->getFieldSettings()['handler_settings']['view']['view_name']));
      $view->execute($this->getFieldSettings()['handler_settings']['view']['display_name']);
      foreach ($view->result as $row) {
        $row_output = $view->style_plugin->view->rowPlugin->render($row);
        $options[$row->_entity->id()] = $options[$row->_entity->id()]->create($this->renderer->render($row_output));
      }
    }
    // If required and there is one single option, preselect it.
    if ($this->required && count($options) == 1) {
      reset($options);
      $selected = array(key($options));
    }

    $element += array(
      '#type' => 'radios',
      '#theme' => 'ila_radios',
      // Radio buttons need a scalar value. Take the first default value, or
      // default to NULL so that the form element is properly recognized as
      // not having a default value.
      '#default_value' => $selected ? reset($selected) : NULL,
      '#options' => $options,
      '#after_build' => array('ila_stands_number_process_radios'),
    );


    return $element;
  }
}
