<?php

namespace Drupal\ila_video_popup\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Url;
use Drupal\video\Plugin\Field\FieldFormatter\VideoEmbedPlayerFormatter;

/**
 * Plugin implementation of the video popup field formatter.
 *
 * @FieldFormatter(
 *   id = "ila_video_embed_popup",
 *   label = @Translation("Embedded Video Player (in popup)"),
 *   field_types = {
 *     "video"
 *   }
 * )
 */
class VideoEmbedPopup extends VideoEmbedPlayerFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    if (!$items->isEmpty()) {
      $settings = $this->getSettings();
      $video = $elements[0];
      // Hacky solution
      $video['#attributes']['src'] = $video['#attributes']['src'] .
        '&modestbranding=0&showinfo=0&controls=0&loop=1&rel=0';

      /** @var \Drupal\Core\Render\RendererInterface $renderer */
      $renderer = \Drupal::service('renderer');
      $video_html = $renderer->render($video);

      $elements[0] = [
        '#type' => 'link',
        '#title' => '',
        '#text' => $this->t('Play'),
        '#url' => Url::fromUri('internal:'),
        '#attributes' => [
          'class' => ['video-popup__trigger'],
        ],
      ];

      $colorbox_settings = \Drupal::config('colorbox.settings')->get('custom');
      $colorbox_settings['className'] = 'video-popup';

      $elements['#attached']['drupalSettings']['ila_video_popup_colorbox'] = $colorbox_settings;
      $elements['#attached']['library'][] = 'ila_video_popup/popup';
      $elements['#attached']['drupalSettings']['ila_video_popup'] = [
        'html' => (string) $video_html,
      ];
    }

    return $elements;
  }

}
