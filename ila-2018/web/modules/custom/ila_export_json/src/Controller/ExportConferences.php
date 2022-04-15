<?php

namespace Drupal\ila_export_json\Controller;

use DateTime;
use DateTimeZone;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\ila_data_export\IlaExportDataInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for export json.
 */
class ExportConferences extends ControllerBase {

  /**
   * @inheritdoc
   */
  public function getJsonData($entity_type) {
    $json_array = [
      'data' => [],
    ];

    switch ($entity_type) {
      case 'conference':
        $this->getConferenceData($entity_type, $json_array);
        break;

      default:
        return FALSE;
    }

    return $json_array;
  }

  /**
   * @inheritdoc
   */
  public function exportConferences() {
    $entity_type = 'conference';
    $data = $this->getJsonData($entity_type);

    return new JsonResponse($data);
  }

  /**
   * {@inheritdoc}
   */
  public function getConferenceData($entity_type, &$json_array) {
    $count = 0;
    $relevant_date = new DrupalDateTime('2020-01-01');
    $nids = \Drupal::entityQuery('node')
      ->condition('type', $entity_type)
      ->condition('created', $relevant_date->getTimestamp(), '>=')
      ->execute();
    $nodes = Node::loadMultiple($nids);
    $categories = [];
    $topics = [];
    foreach ($nodes as $node) {
      $languages = $node->getTranslationLanguages();
      $status = FALSE;
      foreach ($languages as $key => $lang) {
        if ($node->getTranslation($key)->get('status')->value == '1') {
          $status = TRUE;
        }
      }
      if ($status) {
        $field_event_category = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->load((int) $node->get('field_event_category')->target_id);
        $field_topic = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->load((int) $node->get('field_topic')->target_id);
        $begin_date = new DateTime($node->get('field_date_and_time')->value, new DateTimeZone('UTC'));
        $begin_date = $begin_date->format('Y-m-d\TH:i:sO');
        $end_date = new DateTime($node->get('field_date_and_time')->end_value, new DateTimeZone('UTC'));
        $end_date = $end_date->format('Y-m-d\TH:i:sO');
        $invitation_only = $node->get('field_co_invitation_only')->getValue()[0]['value'];

        $array_item = [
          'type' => $entity_type,
          'id' => $node->id(),
          'attributes' => [
            'title' => [],
            'location_string' => [],
            'begin_date' => $begin_date,
            'end_date' => $end_date,
            'teaser' => [],
            'program' => [],
            'invitation_only' => $invitation_only
          ]
        ];
        if (isset($field_event_category)) {
          $array_item['relationships']['categories'] = [(string) $field_event_category->id()];
        }
        if (isset($field_topic)) {
          $array_item['relationships']['topics'] = [(string) $field_topic->id()];
        }

        $json_array['data'][] = $array_item;
        if (isset($field_event_category)) {
          $categories[] = $field_event_category->id();
        }
        if (isset($field_topic)) {
          $topics[] = $field_topic->id();
        }
        foreach ($languages as $key => $lang) {
          $node = $node->getTranslation($key);
          $json_array['data'][$count]['attributes']['title'][$key] = mb_convert_encoding($node->get('title')->value, "UTF-8");
          $json_array['data'][$count]['attributes']['location_string'][$key] = mb_convert_encoding($node->get('field_co_location')->value, "UTF-8");
          $json_array['data'][$count]['attributes']['teaser'][$key] = mb_convert_encoding($node->get('field_co_teaser_text')->value, "UTF-8");
          $json_array['data'][$count]['attributes']['program'][$key] = mb_convert_encoding($node->get('field_co_programm')->value, "UTF-8");
        }
        $count++;
      }
    }

    $this->fillTermReferenceData(array_unique($categories), 'categories', $count, $json_array);
    $this->fillTermReferenceData(array_unique($topics), 'topics', $count, $json_array);

    return $json_array;
  }

  /**
   * Helper function to filling term references.
   *
   * @param array $terms
   *   Array of terms.
   * @param $type
   *   Type of term.
   * @param $count
   *   End-to-end numbering.
   * @param $json_array
   *   Result array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function fillTermReferenceData(array $terms, $type, &$count, &$json_array) {
    foreach ($terms as $term) {
      $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load((int) $term);
      $term_en = \Drupal::service('entity.repository')->getTranslationFromContext($term, 'en');
      $term_de = \Drupal::service('entity.repository')->getTranslationFromContext($term, 'de');
      $json_array['data'][] = array(
        'type' => $type,
        'id' => (string)$term->id(),
        'attributes' => array(
          'title' => array(
            'de' => mb_convert_encoding($term_de->name->value, "UTF-8"),
            'en' => mb_convert_encoding($term_en->name->value, "UTF-8"),
          )
        )
      );
      $count++;
    }
  }
}
