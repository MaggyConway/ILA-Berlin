<?php

namespace Drupal\ila_import_job_offer\Plugin\QueueWorker;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\File\FileSystem;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

/**
 * Sample Queue Worker.
 *
 * @QueueWorker(
 *   id = "job_offer_queue_worker",
 *   title = @Translation("Sample Queue Worker: Job Offer"),
 *   cron = {"time" = 60}
 * )
 */
class JobOfferQueueItem extends QueueWorkerBase {

  const BASE_URL = 'https://pictures.attention-ngn.com/';
  const DESTINATION = 'job-offer-logo';

  public function processItem($job_offer) {
    if ($job_offer['id']) {
      $career_level = '';
      $contract_type = '';
      $field_job_logo = '';
      $field_job_location = [];
      $field_job_starting_date = '';
      $contact_person = [];

      $connection = Database::getConnection();
      $database_select = $connection->select('node__field_job_external_offer_id', 'extid')
        ->fields('extid', array('entity_id'))
        ->condition('field_job_external_offer_id_value', $job_offer['id'], '=');
      $data = $database_select->execute();
      $result = $data->fetchAll();

      if (isset($job_offer["relationships"]["careerLevel"]["data"][0]["attributes"]["localized"])) {
        $career_level = $this->getOrCreateTerm($job_offer["relationships"]["careerLevel"]["data"][0]["attributes"]["localized"], 'career_level');
      }
      if (isset($job_offer["relationships"]["employment"]["data"][0]["attributes"]["localized"])) {
        $contract_type = $this->getOrCreateTerm($job_offer["relationships"]["employment"]["data"][0]["attributes"]["localized"], 'contract_type');
      }
      if (isset($job_offer["relationships"]["images"]["data"][0]["id"]) && isset($job_offer["relationships"]["images"]["data"][0]['attributes']['url'])) {
        $field_job_logo = $this->createJobOfferLogo($job_offer["relationships"]["images"]["data"][0]["id"], $job_offer["relationships"]["images"]["data"][0]['attributes']['url']);
      }
      if (isset($job_offer["relationships"]["address"]["data"][0]["attributes"])) {
        $field_job_location = $this->setAddressField($job_offer["relationships"]["address"]["data"][0]["attributes"]);
      }
      if (isset($job_offer["relationships"]["jobofferContactPerson"]["data"][0]["attributes"])) {
        $contact_person = $this->setContactPerson($job_offer["relationships"]["jobofferContactPerson"]["data"][0]["attributes"]);
      }
      if(isset($job_offer["attributes"])) {
        $field_job_starting_date = $this->setStartingDate($job_offer["attributes"]);
      }
      $localized_en = $job_offer["attributes"]["localized"]["en_GB"];
      $localized_de = $job_offer["attributes"]["localized"]["de_DE"];

      if ($result) {
        $result = array_pop($result);
        $node = Node::load($result->entity_id);
        if ($node) {
          $node->setTitle($localized_en['title']);
          $this->setEnFieldData($node, $job_offer, $localized_en, $contact_person, $contract_type, $career_level, $field_job_location, $field_job_logo, $field_job_starting_date);
          // Add translation.
          $node = $node->getTranslation('de');
          $node->setTitle($localized_de['title']);
          $this->setDeFieldData($node, $job_offer, $localized_de, $field_job_location, $field_job_starting_date);
          $node->save();
        }
      }
      else {
        $node = Node::create([
          'type' => 'job_offer',
          'langcode' => 'en',
          'uid' => 1,
          'status' => 1,
          'title' => $localized_en['title'],
        ]);

        $this->setEnFieldData($node, $job_offer, $localized_en, $contact_person, $contract_type, $career_level, $field_job_location, $field_job_logo, $field_job_starting_date);
        // Add translation.
        $node = $node->addTranslation('de');
        $node->setTitle($localized_de['title']);
        $this->setDeFieldData($node, $job_offer, $localized_de, $field_job_location, $field_job_starting_date);
        $node->save();
      }
    }
  }

  /**
   * Helper function to set values to fields,
   *
   * @param mixed $node
   *   Node object.
   * @param array $job_offer
   *   Data from JSON.
   * @param array $localized_en
   *   Data for current language.
   * @param array $contact_person
   *   Contat person data.
   * @param mixed $contract_type
   *   Term target id.
   * @param mixed $career_level
   *    Term target id.
   * @param array $field_job_location
   *   Address data.
   * @param mixed $field_job_logo
   *   Logo target id.
   * @param array $field_job_starting_date
   *   Starting date.
   */
  private function setEnFieldData(&$node, $job_offer, $localized_en, $contact_person, $contract_type, $career_level, $field_job_location, $field_job_logo, $field_job_starting_date) {
    if (isset($contact_person) && !empty($contact_person)) {
      $node->field_job_contact->value = $contact_person['contact_person'] ? $contact_person['contact_person'] : '';
      $node->field_job_contact->format = 'filtered_html';
      $node->field_job_contact_person_email->value = $contact_person['contact_person_email'] ? $contact_person['contact_person_email'] : '';
    }
    if (isset($contract_type) && !empty($contract_type)) {
      $node->set('field_job_contract_type',  $contract_type);
    }
    $node->field_job_description->value = $localized_en['description'] ? $localized_en['description'] : '';
    $node->field_job_description->format = 'filtered_html';
    if (isset($career_level) && !empty($career_level)) {
      $node->set('field_job_career_level',  $career_level);
    }
    $node->field_job_email->value = $job_offer["relationships"]["company"]["data"][0]["attributes"]["email"] ? $job_offer["relationships"]["company"]["data"][0]["attributes"]["email"] : '';
    if (isset($field_job_location) && !empty($field_job_location) && isset($field_job_location["localized_en"])) {
      $node->set('field_job_location',  $field_job_location["localized_en"]);
    }
    $node->field_job_offerer->value = $job_offer["relationships"]["company"]["data"][0]["attributes"]["localized"]["en_GB"]["website"] ? $job_offer["relationships"]["company"]["data"][0]["attributes"]["localized"]["en_GB"]["website"] : '';
    if (isset($field_job_logo) && !empty($field_job_logo)) {
      $node->field_job_logo->target_id = $field_job_logo->id();
    }
    $node->field_job_requirements->value = $localized_en['requirements'] ? $localized_en['requirements'] : '';
    $node->field_job_requirements->format = 'filtered_html';
    $node->field_job_starting_date->value = $field_job_starting_date['en'] ? $field_job_starting_date['en'] : '';
    $node->field_job_external_offer_id->value = $job_offer["id"];
  }

  /**
   * Helper function to set values to fields for translation,
   *
   * @param mixed $node
   *   Node object.
   * @param array $job_offer
   *   Data from JSON.
   * @param $localized_de
   *   Data for current language.
   * @param array $field_job_location
   *   Address data.
   * @param array $field_job_starting_date
   *   Starting date.
   */
  private function setDeFieldData(&$node, $job_offer, $localized_de, $field_job_location, $field_job_starting_date) {
    $node->field_job_description->value = $localized_de['description'] ? $localized_de['description'] : '';
    $node->field_job_description->format = 'filtered_html';
    if (isset($field_job_location) && !empty($field_job_location) && isset($field_job_location["localized_de"])) {
      $node->set('field_job_location',  $field_job_location["localized_de"]);
    }
    $node->field_job_offerer->value = $job_offer["relationships"]["company"]["data"][0]["attributes"]["localized"]["de_DE"]["website"] ? $job_offer["relationships"]["company"]["data"][0]["attributes"]["localized"]["de_DE"]["website"] : '';
    $node->field_job_requirements->value = $localized_de['requirements'] ? $localized_de['requirements'] : '';
    $node->field_job_requirements->format = 'filtered_html';
    $node->field_job_starting_date->value = $field_job_starting_date['de'] ? $field_job_starting_date['de'] : '';
  }

  /**
   * Helper function for creating new terms or get existing.
   *
   * @param array $term
   *   Array of term data.
   * @param string $vocabulary_id
   *   Vocabulary machine name.
   *
   * @return int
   *   Return term id.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function getOrCreateTerm($term, $vocabulary_id) {
    $taxonomy = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['name' => $term["en_GB"]["name"], 'vid' => $vocabulary_id]);

    if (empty($taxonomy)) {
      $new_term = Term::create([
        'name' => $term["en_GB"]["name"],
        'vid' => $vocabulary_id,
      ]);
      $new_term->addTranslation('de', [
        'name' => $term["de_DE"]["name"],
      ])->save();
      $term_id = $new_term->id();
    }
    else {
      $term_id = key($taxonomy);
    }

    return $term_id;
  }

  /**
   * Helper function to creating media entity.
   *
   * @param $image_id
   *   External image id.
   * @param $external_url
   *   External url for file.
   *
   * @return bool|\Drupal\Core\Entity\EntityInterface|\Drupal\Core\Entity\EntityInterface[]|Media
   *   Return Media object.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function createJobOfferLogo($image_id, $external_url) {
    $directory = 'public://' . JobOfferQueueItem::DESTINATION . '/';
    \Drupal::service('file_system')->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    $file = system_retrieve_file(JobOfferQueueItem::BASE_URL . $external_url, $directory,TRUE);

    if (!$file) {
      return FALSE;
    }
    $media_image = \Drupal::entityTypeManager()->getStorage('media')->loadByProperties(['field_joboffer_external_image_id' => $image_id]);
    if ($media_image) {
      return array_shift($media_image);
    }
    else {
      $media_image = Media::create([
        'bundle' => 'image',
        'name' => $file->getFilename(),
        'field_media_image' => [
          'target_id' => $file->id(),
        ],
        'field_joboffer_external_image_id' => $image_id,
      ]);
      try {
        $media_image->save();
      } catch (EntityStorageException $e) {
      }
    }

    return $media_image ? $media_image : FALSE;
  }

  /**
   * Helper function to combine an address array.
   *
   * @param array $address_data
   *   Address data.
   *
   * @return array|bool
   *   Return address array.
   */
  private function setAddressField(array $address_data) {
    $localized_en = $address_data['localized']['en_GB'];
    $localized_de = $address_data['localized']['de_DE'];

    $countries = \Drupal\Core\Locale\CountryManager::getStandardList();
    foreach ($countries as $key_country => $value) {
      if ($localized_en['country'] == $value) {
        $country_code = $key_country;
        break;
      }
    }

    if (!$country_code) {
      return FALSE;
    }

    $address = [
      'localized_en' => [
        'country_code' => $country_code,
        'address_line1' => $localized_en['region'] . ' ' . $localized_en['street'],
        'locality' => $localized_en['city'],
        'postal_code' => $localized_en['zipCode'],
      ],
      'localized_de' => [
        'country_code' => $country_code,
        'address_line1' =>  $localized_de['region'] . ' ' . $localized_de['street'],
        'locality' => $localized_de['city'],
        'postal_code' => $localized_de['zipCode'],
      ],
    ];

    return $address;
  }

  /**
   * Helper function to combine contact person data.
   *
   * @param array $contact_person_data
   *   Array of the contact person data.
   *
   * @return array
   *   Return contact person array.
   */
  private function setContactPerson(array $contact_person_data) {
    $contact_person['contact_person'] = '';
    $contact_person['contact_person_email'] = '';

    if (isset($contact_person_data['firstName']) && !empty($contact_person_data['firstName'])) {
      $contact_person['contact_person'] = $contact_person_data['firstName'];
    }
    if (isset($contact_person_data['lastName']) && !empty($contact_person_data['lastName'])) {
      $contact_person['contact_person'] .= ' ' . $contact_person_data['lastName'];
    }
    if (isset($contact_person_data['companyName']) && !empty($contact_person_data['companyName']) && isset($contact_person_data['companyName'][0])) {
      $contact_person['contact_person'] .= ' <br>' . $contact_person_data['companyName'][0];
    }
    if (isset($contact_person_data['email']) && !empty($contact_person_data['email'])) {
      $contact_person['contact_person_email'] = $contact_person_data['email'];
    }

    return $contact_person;
  }

  /**
   * Helper function to set starting date.
   *
   * @param array $joboffer_info
   *   Array of the joboffer info.
   *
   * @return bool|TranslatableMarkup|mixed
   *   Return starting date.
   */
  private function setStartingDate(array $joboffer_info) {
    $field_job_starting_date['en'] = '';
    $field_job_starting_date['de'] = '';

    if ($joboffer_info["joiningDate"]) {
      $field_job_starting_date['en'] = $joboffer_info["joiningDate"];
      $field_job_starting_date['de'] = $joboffer_info["joiningDate"];
    }
    elseif ($joboffer_info["joiningDateImmediately"]) {
      $field_job_starting_date['en'] = 'Immediately';
      $field_job_starting_date['de'] = 'ab sofort';
    }

    return $field_job_starting_date;
  }

}
