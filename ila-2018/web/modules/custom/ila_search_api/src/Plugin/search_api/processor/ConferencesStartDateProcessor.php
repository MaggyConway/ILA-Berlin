<?php

namespace Drupal\ila_search_api\Plugin\search_api\processor;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityInterface;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\IndexInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * @SearchApiProcessor(
 *   id = "ila_conferences_start_date",
 *   label = @Translation("Conferences start date"),
 *   description = @Translation("Conferences Search API start date."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   hidden = true,
 *   locked = true,
 * )
 */
class ConferencesStartDateProcessor extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public static function supportsIndex(IndexInterface $index) {
    foreach ($index->getDatasources() as $datasource) {
      if ($datasource->getEntityTypeId() == 'node') {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Conferences Start Date'),
        'description' => $this->t('Conferences Search API start date.'),
        'type' => 'datetime_iso8601',
        'processor_id' => $this->getPluginId(),
      ];
      $properties['ila_conferences_start_date'] = new ProcessorProperty($definition);
    }

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    /** @var EntityInterface $entity */
    $entity = $item->getOriginalObject()->getValue();

    if ($entity instanceof EntityInterface && $entity->hasField('field_date_and_time')) {
      $field_date_and_time = $entity->get('field_date_and_time')->getValue();

      if ($field_date_and_time) {
        $start_date_time = new DrupalDateTime($field_date_and_time[0]["value"]);
        $start_date_time = $start_date_time->getTimestamp();
        //$start_date_time = $start_date_time->format('l, j. F Y');

        // Add value to index.
        $fields = $this->getFieldsHelper()
          ->filterForPropertyPath($item->getFields(), NULL, 'ila_conferences_start_date');
        foreach ($fields as $field) {
          $field->addValue($start_date_time);
        }
      }
    }
  }

}
