<?php

namespace Drupal\ila_import_aircraft\Plugin\QueueWorker;
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
 *   id = "aircraft_queue_worker",
 *   title = @Translation("Sample Queue Worker: Aircraft"),
 *   cron = {"time" = 60}
 * )
 */
class AircraftQueueItem extends QueueWorkerBase {

  public function processItem($art)
  {
    $connection = Database::getConnection();
    if ($art['category en']) {
      $taxonomy = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadByProperties(['name' => $art['category en'], 'vid' => 'aircraft_category']);
      if (empty($taxonomy)) {
        $term = Term::create([
          'name' => $art['category en'],
          'vid' => 'aircraft_category',
        ]);
        $term->addTranslation('de', [
          'name' => $art['category de'],
        ])->save();
        $taxonomy = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->loadByProperties(['name' => $art['category en'], 'vid' => 'aircraft_category']);
      }
      $sth = $connection->select('node__field_air_ila_nr', 'nr')
        ->fields('nr', array('entity_id'))
        ->condition('field_air_ila_nr_value', $art['ila-nr.'], '=');
      $data = $sth->execute();
      $results = $data->fetchAll();
      foreach ($taxonomy as $key => $term) {
        $term_id = $term->id();
        if (count($results) > 0) {
          foreach ($results as $result) {
            $node = Node::load($result->entity_id);
            if ($node) {
              $node->title->value = $art['ac type long'] . ' ILA-Nr. ' . $art['ila-nr.'];
              $node->field_air_pub_name->value = $art['pub name'];
              $node->field_air_ila_nr->value = $art['ila-nr.'];
              $node->field_air_ac_type_long->value = $art['ac type long'];
              $node->field_air_category->target_id = $term_id;
              $node->field_air_country->value = $art['country'];
              $node->field_air_length_m_->value = $art['length [m]'];
              $node->field_air_mtow_kg_->value = $art['mtow [kg]'];
              $node->field_air_op_status->value = $art['op status de'];
              $node->field_air_span_m_->value = $art['span [m]'];
              $node->field_air_initial_arrival->value = substr($art['initial arrival'], 0, -1);
              $node->field_air_final_departure->value = substr($art['final departure'], 0, -1);
              $node->changed->value = (string)\Drupal::time()->getCurrentTime();
              $node = $node->getTranslation('en');
              $node->title->value = $art['ac type long'] . ' ILA-Nr. ' . $art['ila-nr.'];
              $node->field_air_ila_nr->value = $art['ila-nr.'];
              $node->field_air_pub_name->value = $art['pub name'];
              $node->field_air_ac_type_long->value = $art['ac type long'];
              $node->field_air_category->target_id = $term_id;
              $node->field_air_country->value = $art['country'];
              $node->field_air_length_m_->value = $art['length [m]'];
              $node->field_air_mtow_kg_->value = $art['mtow [kg]'];
              $node->field_air_op_status->value = $art['op status en'];
              $node->field_air_span_m_->value = $art['span [m]'];
              $node->field_air_initial_arrival->value = substr($art['initial arrival'], 0, -1);
              $node->field_air_final_departure->value = substr($art['final departure'], 0, -1);
              $node->changed->value = (string)\Drupal::time()->getCurrentTime();
              $node->save();
            }
          }
        } else {
          $node = Node::create(array(
            'type' => 'aircraft',
            'langcode' => 'de',
            'uid' => 1,
            'status' => 1,
            'title' => $art['ac type long'] . ' ILA-Nr. ' . $art['ila-nr.'],
            'field_air_ila_nr' => $art['ila-nr.'],
            'field_air_pub_name' => $art['pub name'],
            'field_air_ac_type_long' => $art['ac type long'],
            'field_air_category' => $term_id,
            'field_air_country' => $art['country'],
            'field_air_length_m_' => $art['length [m]'],
            'field_air_mtow_kg_' => $art['mtow [kg]'],
            'field_air_op_status' => $art['op status de'],
            'field_air_span_m_' => $art['span [m]'],
            'field_air_initial_arrival' => substr($art['initial arrival'], 0, -1),
            'field_air_final_departure' => substr($art['final departure'], 0, -1),
          ));
          $node = $node->addTranslation('en');
          $node->type->value = 'aircraft';
          $node->uid->value = 1;
          $node->status->value = 1;
          $node->title->value = $art['ac type long'] . ' ILA-Nr. ' . $art['ila-nr.'];
          $node->field_air_ila_nr->value = $art['ila-nr.'];
          $node->field_air_pub_name->value = $art['pub name'];
          $node->field_air_ac_type_long->value = $art['ac type long'];
          $node->field_air_category->target_id = $term_id;
          $node->field_air_country->value = $art['country'];
          $node->field_air_length_m_->value = $art['length [m]'];
          $node->field_air_mtow_kg_->value = $art['mtow [kg]'];
          $node->field_air_op_status->value = $art['op status en'];
          $node->field_air_span_m_->value = $art['span [m]'];
          $node->field_air_initial_arrival->value = substr($art['initial arrival'], 0, -1);
          $node->field_air_final_departure->value = substr($art['final departure'], 0, -1);
          $node->save();
        }
      }
    }
  }
}