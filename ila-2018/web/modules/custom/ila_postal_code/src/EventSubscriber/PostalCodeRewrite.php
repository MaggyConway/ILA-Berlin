<?php

namespace Drupal\ila_postal_code\EventSubscriber;

use CommerceGuys\Addressing\AddressFormat\AdministrativeAreaType;
use Drupal\address\Event\AddressEvents;
use Drupal\address\Event\AddressFormatEvent;
use Drupal\address\Event\SubdivisionsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Adds a county field and a predefined list of counties for Great Britain.
 *
 * Counties are not provided by the library because they're not used for
 * addressing. However, sites might want to add them for other purposes.
 */
class PostalCodeRewrite implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[AddressEvents::ADDRESS_FORMAT][] = ['onAddressFormat'];
    $events[AddressEvents::SUBDIVISIONS][] = ['onSubdivisions'];
    return $events;
  }

  /**
   * Alters the address format for Great Britain.
   *
   * @param \Drupal\address\Event\AddressFormatEvent $event
   *   The address format event.
   */
  public function onAddressFormat(AddressFormatEvent $event) {
    $definition = $event->getDefinition();
    if (!in_array('postalCode', $definition['required_fields'])) {
      $definition['required_fields'][] = 'postalCode';
    }
    if (!in_array('locality', $definition['required_fields'])) {
      $definition['required_fields'][] = 'locality';
    }
    $definition['subdivision_depth'] = 0;
    $event->setDefinition($definition);
  }

  /**
   * Provides the subdivisions for Great Britain.
   *
   * Note: Provides just the Welsh counties. A real subscriber would include
   * the full list, sourced from the CLDR "Territory Subdivisions" listing.
   *
   * @param \Drupal\address\Event\SubdivisionsEvent $event
   *   The subdivisions event.
   */
  public function onSubdivisions(SubdivisionsEvent $event) {
  }
}
