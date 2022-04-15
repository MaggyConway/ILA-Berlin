<?php

namespace Drupal\ila_attendee_registration_block\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * Provides a 'Attendee Registration' condition.
 *
 * @Condition(
 *   id = "attendee_registration",
 *   label = @Translation("Attendee Registration"),
 * )
 */
class AttendeeRegistrationCondition extends ConditionPluginBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   *
   * Checks if the Main Exhibitor form is filled in for current user. If not,
   * deny access to the page.
   */
  public function evaluate() {
    if (!$node = $this->getAttendeeRegistrationNode(\Drupal::currentUser())) {
      if ($this->isConfirmPage()) {
        \Drupal::messenger()->addMessage($this->t('To access this page, please fill in the Attendee Registration form'), 'error');
      }

      return FALSE;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    if ($this->isNegated()) {
      return $this->t('Attendee Registration form must be NOT filled by logged in user');
    }

    return $this->t('Attendee Registration form must be filled by logged in user');
  }

  /**
   * {@inheritdoc}
   */
  private function conferenceId() {

    $conference_id = '';
    $uri_segments = explode('/', \Drupal::service('path.current')->getPath());
    if (intval($uri_segments[2]) && intval($uri_segments[2]) !=0 ) {
      $conference_id = intval($uri_segments[2]);
    }

    return $conference_id;
  }

  /**
   * Checks if current page is request page.
   */
  private function isConfirmPage() {
    $pages = [
      'conference/' . $this->conferenceId(). '/attendee/confirm',
      'conference/' . $this->conferenceId(). '/attendee/complete',
    ];

    $current_url = Url::fromRoute('<current>')->getInternalPath();

    return array_search($current_url, $pages) !== FALSE;
  }

  /**
   * Returns the latest Main Exhibitor node object for the given user.
   */
  protected function getAttendeeRegistrationNode(AccountInterface $account) {
    $values = [
      'user_id' => $account->id(),
      'field_ar_conference' => $this->conferenceId(),
    ];

    $entity_attendee = \Drupal::entityTypeManager()->getStorage('attendee_registration')->loadByProperties($values);

    return end($entity_attendee);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = parent::getCacheContexts();

    return $contexts;
  }

}
