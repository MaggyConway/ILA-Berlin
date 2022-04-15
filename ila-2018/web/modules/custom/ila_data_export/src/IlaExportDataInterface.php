<?php

namespace Drupal\ila_data_export;

/**
 * Provides an interface for IlaExportDataInterface.
 */
interface IlaExportDataInterface {

  /**
   * Helper function to generate json.
   *
   * @param $entity_type
   *   Entity type.
   * @param $tag
   *   Name of the term
   *
   * @return \Symfony\Component\HttpFoundation\Response|bool
   */
  public function getJsonData($entity_type, $tag);

  /**
   * Export pdf file with attendee.
   *
   * @param $entity_type
   *   Entity type.
   * @param $tag
   *   Name of the term
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function exportPdf($entity_type, $tag);

}
