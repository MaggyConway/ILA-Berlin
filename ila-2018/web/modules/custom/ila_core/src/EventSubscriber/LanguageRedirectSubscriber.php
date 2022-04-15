<?php

namespace Drupal\ila_core\EventSubscriber;

use Drupal\Component\Utility\UserAgent;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\language\ConfigurableLanguageManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class LanguageRedirectSubscriber.
 *
 * @package Drupal\ila_core
 */
class LanguageRedirectSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\language\ConfigurableLanguageManager definition.
   *
   * @var \Drupal\language\ConfigurableLanguageManager
   */
  protected $languageManager;

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new LanguageRedirectSubscriber object.
   */
  public function __construct(ConfigurableLanguageManager $language_manager, ConfigFactoryInterface $config_factory) {
    $this->languageManager = $language_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST] = ['onKernelRequestLanguageRedirect', 32];

    return $events;
  }

  /**
   * Redirect to language path prefix based on browser langauge.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The Event to process.
   */
  public function onKernelRequestLanguageRedirect(GetResponseEvent $event) {
    $request = clone $event->getRequest();

    $languages = $this->languageManager->getLanguages();
    $config = $this->configFactory->get('language.negotiation')->get('url');

    $request_path = urldecode(trim($request->getPathInfo(), '/'));

    // Do not redirect static files. @todo Another way?
    if (!preg_match('/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp|pdf)$/i', $request_path)) {
      $path_args = explode('/', $request_path);
      $prefix = array_shift($path_args);

      // Search prefix within added languages.
      $negotiated_language = FALSE;
      /** @var \Drupal\Core\Language\LanguageInterface $language */
      foreach ($languages as $language) {
        if (isset($config['prefixes'][$language->getId()]) && $config['prefixes'][$language->getId()] == $prefix) {
          $negotiated_language = $language;
          break;
        }
      }

      if (!$negotiated_language) {
        // Try to get langcode based on browser language.
        $http_accept_language = $request->server->get('HTTP_ACCEPT_LANGUAGE');
        $mappings = $this->configFactory->get('language.mappings')->get('map');
        $langcodes = array_keys($this->languageManager->getLanguages());
        $langcode = UserAgent::getBestMatchingLangcode($http_accept_language, $langcodes, $mappings);

        if (!$langcode) {
          $langcode = $this->languageManager->getDefaultLanguage()->getId();
        }

        $redirect_prefix = $config['prefixes'][$langcode];
        $uri = "/$redirect_prefix/$request_path";
        $response = new RedirectResponse($uri);
        $event->setResponse($response);
      }
    }
  }

}
