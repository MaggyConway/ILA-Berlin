<?php

namespace Drupal\ila_video_local_with_cover_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\media_entity_video\Plugin\Field\FieldFormatter\VideoPlayerHTML5;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'video_player_with_cover' formatter.
 *
 * @FieldFormatter(
 *   id = "video_player_with_cover",
 *   label = @Translation("HTML5 Video Player (with cover)"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class VideoPlayerWithCoverFormatter extends VideoPlayerHTML5 implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The link generator.
   *
   * @var \Drupal\Core\Utility\LinkGeneratorInterface
   */
  protected $linkGenerator;

  /**
   * The image style entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $imageStyleStorage;

  /**
   * Constructs an ImageFormatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Utility\LinkGeneratorInterface $link_generator
   *   The link generator service.
   * @param \Drupal\Core\Entity\EntityStorageInterface $image_style_storage
   *   The entity storage for the image.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, AccountInterface $current_user, LinkGeneratorInterface $link_generator, EntityStorageInterface $image_style_storage) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->currentUser = $current_user;
    $this->linkGenerator = $link_generator;
    $this->imageStyleStorage = $image_style_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('current_user'),
      $container->get('link_generator'),
      $container->get('entity_type.manager')->getStorage('image_style')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'image_field' => '',
        'image_style' => ''
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    // Get display mode fields.
    $image_fields = $this->getImageFields();
    // Create select element.
    $form['image_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Image field'),
      '#description' => $this->t('Select a field of which value will be used as a poster image for rendering.'),
      '#options' => $image_fields,
      '#empty_option' => $this->t('Select Poster Image'),
      '#default_value' => $this->getSetting('image_field'),
      '#access' => count($image_fields) > 1,
    ];

    $image_styles = image_style_options(FALSE);

    $form['image_style'] = array(
      '#title' => t('Image style'),
      '#type' => 'select',
      '#default_value' => $this->getSetting('image_style'),
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
      '#description' => array(
        '#markup' => $this->linkGenerator->generate($this->t('Configure Image Styles'), new Url('entity.image_style.collection')),
        '#access' => $this->currentUser->hasPermission('administer image styles'),
      ),
      '#states' => [
        'visible' => [
          'select[name="fields[field_media_video_file][settings_edit_form][settings][image_field]"]' => ['!value' => ''],
        ],
      ],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    if (!empty($this->getSetting('image_field'))) {
      $summary[] = t('Poster image: @image_field', array('@image_field' => $this->getSetting('image_field')));
      $image_styles = image_style_options(FALSE);

      // Unset possible 'No defined styles' option.
      unset($image_styles['']);

      // Styles could be lost because of enabled/disabled modules that defines
      // their styles in code.
      if (!empty($image_styles[$this->getSetting('image_style')])) {
        $summary[] = t('Image style: @style', array('@style' => $image_styles[$this->getSetting('image_style')]));
      }
      else {
        $summary[] = t('Image style: @style', array('@style' => t('Original image')));
      }
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    if ($poster = $this->extractPoster($items)) {
      foreach ($elements as $delta => $element) {
        $elements[$delta]['#extravalue'] .= ' poster=' . $poster;
      }
    }

    return $elements;
  }

  /**
   * Extract poster image from thumbnail field.
   */
  protected function extractPoster(FieldItemListInterface $items) {
    $poster_field_name = $this->getSetting('image_field');
    if ($poster_field_name) {
      /** @var \Drupal\Core\Entity\ContentEntityInterface $parent_entity */
      $parent_entity = $items->getParent()->getValue();

      if ($parent_entity->hasField($poster_field_name)) {
        $poster_field = $parent_entity->get($poster_field_name);
        if (!$poster_field->isEmpty()) {

          /** @var \Drupal\file\FileInterface $file */
          $file = $poster_field->entity;
          $image_style_setting = $this->getSetting('image_style');

          // Collect cache tags to be added for each item in the field.
          $image_style = $this->imageStyleStorage->load($image_style_setting);

          if ($image_style) {
            $cache_tags = $image_style->getCacheTags();
            $cache_tags = Cache::mergeTags($cache_tags, $file->getCacheTags());

            return $image_style->buildUrl($file->getFileUri());
          }
          else {
            return file_create_url($file->getFileUri());
          }
        }
      }
    }

    return NULL;
  }

  /**
   * Retrieve available image fields.
   */
  private function getImageFields() {
    $display_fields = [];
    // Get all fields associated with current entity.
    $entity_type = $this->fieldDefinition->getTargetEntityTypeId();
    $entity_bundle = $this->fieldDefinition->getTargetBundle();
    $entity_fields = \Drupal::service('entity_field.manager')
      ->getFieldDefinitions($entity_type, $entity_bundle);
    /** @var \Drupal\Core\Field\FieldDefinitionInterface $field */
    foreach ($entity_fields as $key => $field) {
      // Find image fields.
      if ($field->getType() == 'image') {
        $display_fields[$key] = $field->getLabel();
      }
    }

    return $display_fields;
  }
}
