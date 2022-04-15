<?php

namespace Drupal\ila_download_ics\Controller;

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\File\FileSystem;

/**
 * An example controller.
 */
class IlaServiceController extends ControllerBase {

  protected $file;

  protected $entity;

  protected $currentUser;

  public $request;

  /**
   * Constructor.
   */
  public function __construct(EntityStorageInterface $entityStorage, AccountProxy $currentuser, RequestStack $request, FileSystem $fileStorage) {
    $this->entity = $entityStorage;
    $this->currentUser = $currentuser;
    $this->request = $request;
    $this->file = $fileStorage;
  }

  /**
   * Create dependency injection.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->EntityTypeManagerInterface::getStorage('node'),
      $container->get('current_user'),
      $container->get('request_stack'),
      $container->get('file_system')
    );

  }

  /**
   * Download ics file.
   */
  public function download($start_date, $end_date) {
    // @TODO Think about moving this to admin config page.
    $summary = 'ILA Berlin 2020';
    $location = 'Berlin Expo Center';
    $f_name = 'ILA Berlin 2020';
    // Convert the date to the Timezone of the user requesting.
    $start_date = new \DateTime($start_date);
    $end_date = new \DateTime($end_date);

    // Get Host.
    $host = \Drupal::request()->getHost();

    // 1. Create a Calendar object.
    $vCalendar = new Calendar($host);

    // 2. Create an Event object.
    $vEvent = new Event();

    // 3. Add your information to the Event.
    $vEvent
      ->setDtStart($start_date)
      ->setDtEnd($end_date)
      ->setSummary($summary)
      ->setLocation($location);

    // 4. Add Event to Calendar.
    $vCalendar->addComponent($vEvent);

    // 5. Send output.
    return $this->generateIcsFile($f_name, $vCalendar);
  }

  /**
   * Download ics file for node.
   * Attention! Works only with date range field type.
   */
  public function downloadEntityIcs($date_field, $nid = NULL) {

    // Load the node.
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    if (empty($node)) {
      return FALSE;
    }
    $summary = $node->getTitle();
    // @TODO Add the checking on empty value and replace by something.
    $location = $node->get('field_co_location')->getString();
    $f_name = $node->getTitle();
    $date_field_value = $node->get($date_field)->getValue();

    $start_date = new \DateTime($date_field_value[0]['value']);
    $end_date = new \DateTime($date_field_value[0]['end_value']);
    // Get Host.
    $host = \Drupal::request()->getHost();

    // 1. Create a Calendar object.
    $vCalendar = new Calendar($host);

    // 2. Create an Event object.
    $vEvent = new Event();

    // 3. Add your information to the Event.
    $vEvent
      ->setDtStart($start_date)
      ->setDtEnd($end_date)
      ->setSummary($summary)
      ->setLocation($location);

    // 4. Add Event to Calendar.
    $vCalendar->addComponent($vEvent);

    // 5. Send output.
    return $this->generateIcsFile($f_name, $vCalendar);
  }


  /**
   * Helper function for generating an ICS file,
   *
   * @param $f_name
   *   The name of the file.
   * @param $vCalendar
   *   A calendar object.
   *
   * @return BinaryFileResponse|Response
   *   Return the generated file.
   */
  private function generateIcsFile($f_name, $vCalendar) {
    // 5. Send output.
    $filename = $f_name . '.ics';
    $uri = 'public://' . $filename;
    $content = $vCalendar->render();
    $file = file_save_data($content, $uri, FILE_EXISTS_REPLACE);
    if (empty($file)) {
      return new Response(
        'Download ICS Error, Please contact the System Administrator'
      );
    }

    $mimetype = 'text/calendar';
    $scheme = 'public';
    $parts = explode('://', $uri);
    $file_directory = \Drupal::service('file_system')->realpath(
      $scheme . "://"
    );
    $filepath = $file_directory . '/' . $parts[1];
    $filename = $file->getFilename();

    // File doesn't exist
    // This may occur if the download path is used outside of a formatter
    // and the file path is wrong or file is gone.
    if (!file_exists($filepath)) {
      throw new NotFoundHttpException();
    }

    $headers = [
      'Content-Type' => $mimetype,
      'Content-Disposition' => 'attachment; filename="' . $filename . '"',
      'Content-Length' => $file->getSize(),
      'Content-Transfer-Encoding' => 'binary',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
      'Expires' => '0',
      'Accept-Ranges' => 'bytes',
    ];

    // \Drupal\Core\EventSubscriber\FinishResponseSubscriber::onRespond()
    // sets response as not cacheable if the Cache-Control header is not
    // already modified. We pass in FALSE for non-private schemes for the
    // $public parameter to make sure we don't change the headers.
    return new BinaryFileResponse($uri, 200, $headers, $scheme !== 'private');
  }

}
