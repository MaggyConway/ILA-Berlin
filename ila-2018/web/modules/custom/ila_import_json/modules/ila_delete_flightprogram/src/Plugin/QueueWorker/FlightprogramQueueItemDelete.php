<?php

namespace Drupal\ila_delete_flightprogram\Plugin\QueueWorker;
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
 *   id = "delete_flightprogram_queue_worker",
 *   title = @Translation("Sample Queue Worker: Flightprogram delete"),
 *   cron = {"time" = 60}
 * )
 */
class FlightprogramQueueItemDelete extends QueueWorkerBase {

  public function processItem($item) {
    $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
    $entities = $storage_handler->loadMultiple(array($item));
    $storage_handler->delete($entities);
  }
}