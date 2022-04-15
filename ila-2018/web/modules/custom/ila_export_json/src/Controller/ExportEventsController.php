<?php

namespace Drupal\ila_export_json\Controller;

use DateTime;
use DateTimeZone;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for export json.
 */
class ExportEventsController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function data() {
    $json_array = array(
      'data' => array()
    );
    $var = 0;
    $nids = \Drupal::entityQuery('node')->condition('type', 'event')->execute();
    $nodes = Node::loadMultiple($nids);
    $categories = array();
    $topics = array();
    foreach ($nodes as $node) {
      $languages = $node->getTranslationLanguages();
      $status = false;
      foreach ($languages as $key => $lang) {
        if ($node->getTranslation($key)->get('status')->value == '1') {
          $status = true;
        }
      }
      if ($node->get('field_event_node_export')->value !== '1' && $status) {
        $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load((int)$node->get('field_event_category')->target_id);
        $field_topic = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load((int)$node->get('field_topic')->target_id);
        $begin_date = new DateTime($node->get('field_date_and_time')->value, new DateTimeZone('UTC'));
        $begin_date = $begin_date->format('Y-m-d\TH:i:sO');
        $end_date = new DateTime($node->get('field_date_and_time')->end_value, new DateTimeZone('UTC'));
        $end_date = $end_date->format('Y-m-d\TH:i:sO');

        $array_item = array(
          'type' => $node->get('type')->target_id,
          'id' => $node->get('nid')->value,
          'attributes' => array(
            'title' => array(),
            'content' => array(),
            'location_string' => array(),
            'begin_date' => $begin_date,
            'end_date' => $end_date,
            'teaser' => array(),
          ),
          'relationships' => array(
            'categories' => array((string) $term->id()),
          )
        );
        if (isset($field_topic)) {
          $array_item['relationships']['topics'] = array((string) $field_topic->id());
        }

        $json_array['data'][] = $array_item;
        $categories[] = $term->id();
        if (isset($field_topic)) {
          $topics[] = $field_topic->id();
        }
        foreach ($languages as $key => $lang) {
          $node = $node->getTranslation($key);
          $json_array['data'][$var]['attributes']['title'][$key] = mb_convert_encoding($node->get('title')->value, "UTF-8");
          $json_array['data'][$var]['attributes']['content'][$key] = mb_convert_encoding($node->get('body')->value, "UTF-8");
          $json_array['data'][$var]['attributes']['location_string'][$key] = mb_convert_encoding($node->get('field_ev_venue')->value, "UTF-8");
          $json_array['data'][$var]['attributes']['teaser'][$key] = mb_convert_encoding($node->get('field_teaser_text')->value, "UTF-8");
        }
        $var++;
      }
    }

    $this->fillTermReferenceData(array_unique($categories), 'categories', $var, $json_array);
    $this->fillTermReferenceData(array_unique($topics), 'topics', $var, $json_array);

    return new JsonResponse($json_array);
  }

  public function aircraft() {
    $json_array = array(
      'data' => array()
    );
    $nids = \Drupal::entityQuery('node')->condition('type','aircraft')->execute();
    $nodes =  Node::loadMultiple($nids);
    $var = 0;
    $categories = array();
    foreach ($nodes as $node) {
      $begin_date = null;
      $end_date = null;

      if (isset($node->get('field_air_display_time')->value) && isset($node->get('field_air_display_time')->end_value)) {
        $languages = $node->getTranslationLanguages();
        $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load((int)$node->get('field_air_category')->target_id);

        $begin_date = new DateTime($node->get('field_air_display_time')->value, new DateTimeZone('UTC'));
        $begin_date = $begin_date->format('Y-m-d\TH:i:sO');

        $end_date = new DateTime($node->get('field_air_display_time')->end_value, new DateTimeZone('UTC'));
        $end_date = $end_date->format('Y-m-d\TH:i:sO');

        $json_array['data'][] = array(
          'type' => 'event',
          'id' => $node->get('nid')->value,
          'attributes' => array(
            'title' => array(),
            'location_string' => array(),
            'begin_date' => $begin_date,
            'end_date' => $end_date
          ),
          'relationships' => array(
            'categories' => array((string) $term->id(), 'aircraft'),
          )
        );
        $categories[] = $term->id();
        foreach ($languages as $key => $lang) {
          $node = $node->getTranslation($key);
          $json_array['data'][$var]['attributes']['title'][$key] = mb_convert_encoding($node->get('title')->value, "UTF-8");
          $json_array['data'][$var]['attributes']['location_string'][$key] = mb_convert_encoding($node->get('field_air_country')->value, "UTF-8");
        }
        $var++;
      }
    }
    $terms = array_unique($categories);
    $this->fillTermReferenceData($terms, 'categories', $var, $json_array);

    return new JsonResponse($json_array);
  }

  /**
   * Helper function to filling term references.
   *
   * @param array $terms
   *   Array of terms.
   * @param $type
   *   Type of term.
   * @param $var
   *   End-to-end numbering.
   * @param $json_array
   *   Result array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function fillTermReferenceData(array $terms, $type, &$var, &$json_array) {
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
      $var++;
    }
  }
}
