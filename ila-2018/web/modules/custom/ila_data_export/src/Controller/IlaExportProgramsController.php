<?php

namespace Drupal\ila_data_export\Controller;

use Dompdf\Dompdf;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\ila_data_export\IlaExportDataInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for export data.
 */
class IlaExportProgramsController extends ControllerBase implements IlaExportDataInterface {

  /**
   * @inheritdoc
   */
  public function getJsonData($entity_type, $tag) {
    $json_array = [
      'data' => [],
    ];

    switch ($entity_type) {
      case 'conference':
        $this->getConferenceData($entity_type, $tag, $json_array);
        break;

      default:
        return FALSE;
    }

    return new JsonResponse($json_array);
  }

  /**
   * @inheritdoc
   */
  public function exportPdf($entity_type, $tag) {
    $data = $this->getJsonData($entity_type, $tag);
    $content = json_decode($data->getContent(), true);
    $result = [];

    $result['#theme'] = 'summary_data_export';
    $result['#content'] = $content;
    $result = \Drupal::service('renderer')->render($result);

    if ($result) {
      // instantiate and use the dompdf class
      $dompdf = new Dompdf();
      $dompdf->loadHtml($result);
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();
      $file_name = 'ILA Future Lab Program #AeroDays2020';
      $dompdf->stream($file_name . ".pdf");

      return new Response($this->t('Pdf was exported.'));
    }

    return new Response();
  }

  /**
   *  Helper function to getting data from nodes delegates and guests.
   *
   * @param $entity_type
   *   Entity type.
   * @param $json_array
   *   Returned data.
   * @param $tag
   *   Name of the term
   */
  public function getConferenceData($entity_type, $tag, &$json_array) {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $entity_nids = [];
    try {
      $query = \Drupal::database()->select('node_field_data', 'nfd');
      $query->condition('nfd.type', $entity_type);
      $query->condition('nfd.langcode', $language);
      $query->condition('nfd.status', 1);
      $query->leftJoin('node__field_topic', 'ft', 'ft.entity_id = nfd.nid');
      $query->leftJoin('taxonomy_term_field_data', 'fd', 'fd.tid = ft.field_topic_target_id');
      $query->leftJoin('node__field_date_and_time', 'dt', 'nfd.nid = dt.entity_id');
      $query->condition('fd.name', $tag);
      $query->condition('nfd.langcode', $language);
      $query->orderBy('dt.field_date_and_time_value');
      $query->fields('nfd', ['nid']);
      $results = $query->execute()->fetchAll();
      $entity_nids = array_unique(array_map('current', $results));
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(t('Can\'t execute a custom query.'));
    }
    $entities = Node::loadMultiple($entity_nids);
    foreach ($entities as $key => $entity) {
      if ($entity instanceof NodeInterface) {
        $entity = $entity->getTranslation($language);
        $nid = $entity->id();
        if ($entity->hasField('field_date_and_time')) {
          $begin_date = new DrupalDateTime($entity->get('field_date_and_time')->value);
          $end_date = new DrupalDateTime($entity->get('field_date_and_time')->end_value);
          try {
            //using 'Europe/Samara' as UTC+4 timezone as it returns correct Berlin time in PDF
            //for some reason 'Europe/Berlin' timezone returned time 2 hours less, than expected
            $json_array['data'][$nid]['event_date'] = \Drupal::service('daterange_compact.date_range.formatter')
              ->formatDateTimeRange($begin_date->getTimestamp(), $end_date->getTimestamp(), 'fl_conferences', 'Europe/Samara');
          } catch (\Exception $e) {
            \Drupal::messenger()->addError(t('Can\'t set a date range format.'));
          }
        }
        $json_array['data'][$nid]['event_name'] = $entity->getTitle();
        if ($entity->hasField('field_co_teaser_text')) {
          if ($field_value = $entity->get('field_co_teaser_text')->getValue()) {
            $json_array['data'][$nid]['event_teaser_text'] = strip_tags($field_value[0]['value']);
          }
          else {
            $json_array['data'][$nid]['event_teaser_text'] = '';
          }
        }
        if ($entity->hasField('field_co_location')) {
          $json_array['data'][$nid]['event_location'] = $entity->get('field_co_location')->getString();
        }
      }
    }
  }
}
