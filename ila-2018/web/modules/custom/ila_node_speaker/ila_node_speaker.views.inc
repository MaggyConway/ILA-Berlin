<?php

/**
 * @file
 * Views hooks.
 */

/**
 * Implements hook_views_data_alter().
 *
 * Alter DB tables defined via hook_views_data().
 */
function ila_node_speaker_views_data_alter(array &$data) {
  $data['node']['ila_node_speaker_start_date'] = [
    'title' => t('Conferences Start Date'),
    'field' => [
      'title' => t('Conferences Start Date'),
      'help' => t('Conferences Start Date.'),
      'id' => 'ila_node_speaker_start_date',
    ],
  ];
}