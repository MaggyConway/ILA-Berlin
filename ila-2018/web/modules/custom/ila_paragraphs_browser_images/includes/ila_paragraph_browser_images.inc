<?php
/**
 *
 */

use Drupal\Core\File\FileSystemInterface;

/**
 * Custom function that can be called to update paragraph browser images.
 */
function _ila_paragraphs_browser_images_update_paragraphs_browser_images() {
  $source_dir = drupal_get_path('module', 'ila_paragraphs_browser_images') . '/images/paragraphs_browser_images';
  $dest_dir = 'public://paragraphs_browser_images';
  $dir = opendir($source_dir);

  try {
    \Drupal::service('file_system')
      ->prepareDirectory($dest_dir, FileSystemInterface::MODIFY_PERMISSIONS | FileSystemInterface::CREATE_DIRECTORY);
  }
  catch (\Drupal\Core\File\Exception\FileException $e) {
    $message = t('Failing to prepare directory.');
    \Drupal::logger('ila_paragraphs_browser_images')->error($message);
  }
  while ($file = readdir($dir)) {
    if ($file != '.' && $file != '..') {
      try {
        \Drupal::service('file_system')->copy($source_dir . '/' . $file, $dest_dir . '/' . $file);
      }
      catch (\Drupal\Core\File\Exception\FileException $e) {
        $message = t('Failing to copy of the file.');
        \Drupal::logger('ila_paragraphs_browser_images')->error($message);
      }
    }
  }
}
