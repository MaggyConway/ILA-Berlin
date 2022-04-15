<?php

namespace Drupal\ila_import_flightprogram\Plugin\QueueWorker;
use Drupal\Component\Datetime\Time;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Sample Queue Worker.
 *
 * @QueueWorker(
 *   id = "flightprogram_queue_worker",
 *   title = @Translation("Sample Queue Worker: Flightprogram"),
 *   cron = {"time" = 60}
 * )
 */
class FlightprogramQueueItem extends QueueWorkerBase {

  public function processItem($art) {
    $connection = Database::getConnection();
    $vid = 'event_category';
    $term_name = 'Flightprogram Today';
    $taxonomy = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $term_name, 'vid' => $vid]);
    if (empty($taxonomy)) {
      $term = Term::create([
        'name' => 'Flightprogram Today',
        'vid' => $vid,
      ]);
      $term->addTranslation('de', [
        'name' => 'Flugprogramm Heute',
      ])->save();
      $taxonomy = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadByProperties(['name' => $term_name, 'vid' => $vid]);
    }
    foreach ($taxonomy as $key => $term) {
      $term_id = $term->id();
      $sth = $connection->select('node__field_ev_ila_nr', 'nr')
        ->fields('nr', array('entity_id'))
        ->condition('field_ev_ila_nr_value', $art['ila-nr.'], '=');
      $data = $sth->execute();
      $results = $data->fetchAll();
      $body_en = 'Exhibitor: ' . $art['pub ex name'] . ', '
        . 'Country: ' . $art['country'] . ', '
        . 'Duration: ' . $art['display duration'] . ' min';
      $body_de = 'Aussteller: ' . $art['pub ex name'] . ', '
        . 'Land: ' . $art['country'] . ', '
        . 'Dauer: ' . $art['display duration'] . ' min';
      $date_explode = explode(" ", $art['sb_cest']);
      $date = explode(".", $date_explode[0]);
      $time = $date_explode[1];
      $date_and_time = $date[2] . '-' . $date[1] . '-' . $date[0] . 'T' . $time . ':00';
      $date = $date[2] . '-' . $date[1] . '-' . $date[0];
      if (count($results) > 0) {
        foreach ($results as $result) {
          $node = Node::load($result->entity_id);
          $node->title->value = $art['pub ac name'] . ' ILA-Nr. ' . $art['ila-nr.'];
          $node->body->value = $body_de;
          $node->field_date->value = $date;
          $node->field_date_and_time->value = $date_and_time;
          $node->field_date_and_time->end_value = $date_and_time;
          $node->field_event_category->target_id = $term_id;
          $node->changed->value = (string)\Drupal::time()->getCurrentTime();
          $node->field_event_node_export = "0";
          $node = $node->getTranslation('en');
          $node->title->value = $art['pub ac name'] . ' ILA-Nr. ' . $art['ila-nr.'];
          $node->body->value = $body_en;
          $node->field_date->value = $date;
          $node->field_date_and_time->value = $date_and_time;
          $node->field_date_and_time->end_value = $date_and_time;
          $node->field_event_category->target_id = $term_id;
          $node->changed->value = (string)\Drupal::time()->getCurrentTime();
          $node->field_event_node_export = "0";
          $node->save();
        }
      } else {
        $node = Node::create(array(
          'type' => 'event',
          'langcode' => 'de',
          'uid' => 1,
          'status' => 1,
          'title' => $art['pub ac name'] . ' ILA-Nr. ' . $art['ila-nr.'],
          'field_date' => $date,
          'field_ev_ila_nr' => $art['ila-nr.'],
          'body' => $body_de,
          'field_date_and_time' => array(
            'value' => $date_and_time,
            'end_value' => $date_and_time),
          'field_event_category' => $term_id,
          'field_ev_venue' => 'Fluggelände',
          'field_event_node_export' => "0",
        ));

        $node = $node->addTranslation('en');
        $node->type->value = 'event';
        $node->uid->value = 1;
        $node->status->value = 1;
        $node->title->value = $art['pub ac name'] . ' ILA-Nr. ' . $art['ila-nr.'];
        $node->field_date->value = $date;
        $node->body->value = $body_en;
        $node->field_date_and_time->value = $date_and_time;
        $node->field_date_and_time->end_value = $date_and_time;
        $node->field_event_category->target_id = $term_id;
        $node->field_ev_venue->value = 'Airfield';
        $node->field_event_node_export = "0";
        $node->save();
      }
    }
  }
}