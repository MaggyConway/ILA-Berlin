<?php

namespace Drupal\ila_file_download_formatter\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Plugin\Field\FieldFormatter\FileFormatterBase;

/**
 * Plugin implementation of the file field formatter.
 *
 * @FieldFormatter(
 *   id = "ila_download_link",
 *   label = @Translation("Download link"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class FileDownloadLink extends FileFormatterBase {

  const LABEL_SOURCE_FILE = 'file';
  const LABEL_SOURCE_PARENT = 'parent';
  const LABEL_SOURCE_CUSTOM = 'custom';

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $default_settings = parent::defaultSettings();

    $default_settings['label_source'] = self::LABEL_SOURCE_PARENT;
    $default_settings['link_text'] = '';
    $default_settings['target'] = '';
    $default_settings['download_file'] = '';

    return $default_settings;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $source = $this->getSetting('label_source');
    $summary[] = $this->t('Label source: @source', ['@source' => $source]);
    if ($source == self::LABEL_SOURCE_CUSTOM) {
      $summary[] = $this->t('Link text: @text', ['@text' => $this->getSetting('link_text')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['label_source'] = [
      '#type' => 'select',
      '#label' => $this->t('Label source'),
      '#default_value' => $this->getSetting('label_source'),
      '#options' => [
        self::LABEL_SOURCE_PARENT => $this->t('Parent'),
        self::LABEL_SOURCE_FILE => $this->t('File'),
        self::LABEL_SOURCE_CUSTOM => $this->t('Custom'),
      ],
    ];
    $form['link_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link text'),
      '#default_value' => $this->getSetting('link_text'),
      '#states' => [
        'visible' => [
          [':input[name$="[label_source]"]' => ['value' => self::LABEL_SOURCE_CUSTOM]],
        ],
      ],
    ];
    $form['target'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Target attribute'),
      '#default_value' => $this->getSetting('target'),
    ];
    $form['download_file'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Download file immediately'),
      '#default_value' => $this->getSetting('download_file'),
    ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    /** @var \Drupal\file\FileInterface $file */
    if (!$items->get(0)) {
      return [];
    }
    $file = $items->get(0)->get('entity')->getValue();

    $build = [];

    $link_text = '';
    switch ($this->getSetting('label_source')) {
      case self::LABEL_SOURCE_FILE:
        $link_text = $file->label();
        break;

      case self::LABEL_SOURCE_PARENT:
        $link_text = $items->getEntity()->label();
        break;

      case self::LABEL_SOURCE_CUSTOM:
        $link_text = $this->t($this->getSetting('link_text'));
        break;
    }

    if ($file->access('download') && $file->createFileUrl(FALSE)) {
      $file_url = Url::fromUri($file->createFileUrl(FALSE));

      if ($this->getSetting('target')) {
        $link_options = [
          'attributes' => [
            'target' => [
              '_blank',
            ],
          ],
        ];
        $file_url->setOptions($link_options);
      }

      if ($this->getSetting('download_file')) {
        $link_options = [
          'attributes' => [
            'download' => [
              $file->label(),
            ],
          ],
        ];
        $file_url->setOptions($link_options);
      }

      $build[] = Link::fromTextAndUrl($link_text, $file_url)->toRenderable();
    }
    else {
      $build[] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['download-forbidden']
        ],
        'text' => [
          '#markup' => $link_text,
        ],
      ];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    $storage = $field_definition->getFieldStorageDefinition();
    return !$storage->isMultiple();
  }

}
