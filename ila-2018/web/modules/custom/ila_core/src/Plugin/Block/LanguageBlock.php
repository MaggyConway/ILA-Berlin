<?php

namespace Drupal\ila_core\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\language\Plugin\Block\LanguageBlock as CoreLanguageBlock;

/**
 * Provides a 'Language switcher' block with configurable links labels.
 *
 * @Block(
 *   id = "ila_language_block",
 *   admin_label = @Translation("Language switcher (Configurable links labels)"),
 *   category = @Translation("System"),
 *   deriver = "Drupal\ila_core\Plugin\Derivative\LanguageBlock"
 * )
 */
class LanguageBlock extends CoreLanguageBlock {

  const LINKS_LABELS_FULL = 'full';
  const LINKS_LABELS_CODE = 'code';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = parent::build();

    if (isset($build['#links'])) {
      $links = &$build['#links'];

      foreach ($links as $delta => $link) {
        /** @var \Drupal\Core\Language\LanguageInterface $language */
        $language = $links[$delta]['language'];

        $links[$delta]['title'] = ($this->configuration['links_labels'] == self::LINKS_LABELS_FULL)
          ? $language->getName()
          : $language->getId();
      }
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $defaults = parent::defaultConfiguration();
    $defaults['links_labels'] = self::LINKS_LABELS_CODE;
    return $defaults;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['links_labels'] = [
      '#type' => 'select',
      '#title' => $this->t('Links labels'),
      '#options' => [
        self::LINKS_LABELS_FULL => $this->t('Full labels'),
        self::LINKS_LABELS_CODE => $this->t('Short code'),
      ],
      '#default_value' => $this->configuration['links_labels'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['links_labels'] = $form_state->getValue('links_labels');

    parent::blockSubmit($form, $form_state);
  }

}
