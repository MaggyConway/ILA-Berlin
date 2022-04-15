<?php

namespace Drupal\ila_facets_block\Plugin\facets\widget;

use Drupal\Core\Form\FormStateInterface;
use Drupal\facets\FacetInterface;
use Drupal\facets\Widget\WidgetPluginBase;

/**
 * The dropdown widget.
 *
 * @FacetsWidget(
 *   id = "ila_dropdown",
 *   label = @Translation("ILA Dropdown (Custom)"),
 *   description = @Translation("A configurable widget that shows a dropdown."),
 * )
 */
class IlaDropdownWidget extends WidgetPluginBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'default_option_label' => 'Choose',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet) {
    $build = parent::build($facet);
    $build['#attributes']['class'][] = 'js-facets-dropdown-links';
    $build['#attached']['drupalSettings']['facets']['dropdown_widget'][$facet->id()]['facet-default-option-label'] = $this->getConfiguration()['default_option_label'];
    $build['#attached']['library'][] = 'ila_facets_block/ila_facets_block.facets.ila-dropdown-widget';
    $build['#attached']['library'][] = 'facets/drupal.facets.general';
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state, FacetInterface $facet) {
    $config = $this->getConfiguration();

    $message = $this->t('To achieve the standard behavior of a dropdown, you need to enable the facet setting below <em>"Ensure that only one result can be displayed"</em>.');
    $form['warning'] = [
      '#markup' => '<div class="messages messages--warning">' . $message . '</div>',
    ];

    $form += parent::buildConfigurationForm($form, $form_state, $facet);

    $form['default_option_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default option label'),
      '#default_value' => $config['default_option_label'],
    ];

    return $form;
  }

}
