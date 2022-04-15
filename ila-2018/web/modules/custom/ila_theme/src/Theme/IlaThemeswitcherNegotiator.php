<?php

namespace Drupal\ila_theme\Theme;

use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * {@inheritdoc}
 */
class IlaThemeswitcherNegotiator implements ThemeNegotiatorInterface {

  /**
   * The path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  protected $pathMatcher;


  public function __construct(PathMatcherInterface $path_mathcer) {
    $this->pathMatcher = $path_mathcer;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return TRUE; //($this->determineActiveTheme($route_match) === 'exhibitor_zone');
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    // URL for theme.
    $current_path = \Drupal::service('path.current')->getPath();
    $node = \Drupal::routeMatch()->getParameter('node');
    $typeName = '';

    if (!empty($node)) {
      $typeName = $node->bundle();
    }
    $is_admin = $this->check_is_admin_path($route_match);

    if (($typeName == 'career_center' || $typeName == 'job_offer' || $current_path === '/bookmarks') && $is_admin == false) {
      return 'ila_career_center';
    }
    elseif (($typeName == 'future_lab') && $is_admin == false) {
      return 'ila_future_lab';
    }
    elseif (strpos($current_path, 'exhibitor-zone')) {
      return 'exhibitor_zone';
    }
  }

  /**
   * Helper function for determinate admin path.
   *
   * @param $route_match
   *   Route object.
   *
   * @return bool
   *   Return boolean value.
   */
  public function check_is_admin_path($route_match) {
    $route = $route_match->getRouteObject();
    $is_admin = FALSE;
    if (!empty($route)) {
      $is_admin_route = \Drupal::service('router.admin_context')->isAdminRoute($route);
      $has_node_operation_option = $route->getOption('_node_operation_route');
      $is_admin = ($is_admin_route || $has_node_operation_option);
    }
    else {
      $current_path = \Drupal::service('path.current')->getPath();
      if(preg_match('/node\/(\d+)\/edit/', $current_path, $matches)) {
        $is_admin = TRUE;
      }
      elseif(preg_match('/taxonomy\/term\/(\d+)\/edit/', $current_path, $matches)) {
        $is_admin = TRUE;
      }
    }
    return $is_admin;
  }
}
