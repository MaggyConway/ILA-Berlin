<?php

namespace Drupal\ila_core\Plugin\Derivative;

use Drupal\language\ConfigurableLanguageManagerInterface;
use Drupal\language\Plugin\Derivative\LanguageBlock as CoreLanguageBlock;

/**
 * Provides language switcher block plugin definitions for all languages.
 */
class LanguageBlock extends CoreLanguageBlock {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    parent::getDerivativeDefinitions($base_plugin_definition);

    $language_manager = \Drupal::languageManager();

    if ($language_manager instanceof ConfigurableLanguageManagerInterface) {
      $configurable_types = $language_manager->getLanguageTypes();
      if (count($configurable_types) == 1) {
        $this->derivatives[reset($configurable_types)]['admin_label'] = t('Language switcher (Configurable links labels)');
      }
    }

    return $this->derivatives;
  }

}
