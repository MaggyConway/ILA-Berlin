<?php

namespace Drupal\ila_attendee_registration\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface;

/**
 * Class AttendeeRegistrationController.
 *
 *  Returns responses for Attendee registration routes.
 */
class AttendeeRegistrationController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Attendee registration  revision.
   *
   * @param int $attendee_registration_revision
   *   The Attendee registration  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($attendee_registration_revision) {
    $attendee_registration = $this->entityTypeManager()->getStorage('attendee_registration')->loadRevision($attendee_registration_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('attendee_registration');

    return $view_builder->view($attendee_registration);
  }

  /**
   * Page title callback for a Attendee registration  revision.
   *
   * @param int $attendee_registration_revision
   *   The Attendee registration  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($attendee_registration_revision) {
    $attendee_registration = $this->entityTypeManager()->getStorage('attendee_registration')->loadRevision($attendee_registration_revision);
    return $this->t('Revision of %title from %date', ['%title' => $attendee_registration->label(), '%date' => \Drupal::service('date.formatter')->format($attendee_registration->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Attendee registration .
   *
   * @param \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface $attendee_registration
   *   A Attendee registration  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(AttendeeRegistrationInterface $attendee_registration) {
    $account = $this->currentUser();
    $langcode = $attendee_registration->language()->getId();
    $langname = $attendee_registration->language()->getName();
    $languages = $attendee_registration->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $attendee_registration_storage = $this->entityTypeManager()->getStorage('attendee_registration');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $attendee_registration->label()]) : $this->t('Revisions for %title', ['%title' => $attendee_registration->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all attendee registration revisions") || $account->hasPermission('administer attendee registration entities')));
    $delete_permission = (($account->hasPermission("delete all attendee registration revisions") || $account->hasPermission('administer attendee registration entities')));

    $rows = [];

    $vids = $attendee_registration_storage->revisionIds($attendee_registration);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\ila_attendee_registration\AttendeeRegistrationInterface $revision */
      $revision = $attendee_registration_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $attendee_registration->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.attendee_registration.revision', ['attendee_registration' => $attendee_registration->id(), 'attendee_registration_revision' => $vid]));
        }
        else {
          $link = $attendee_registration->toLink($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.attendee_registration.translation_revert', ['attendee_registration' => $attendee_registration->id(), 'attendee_registration_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.attendee_registration.revision_revert', ['attendee_registration' => $attendee_registration->id(), 'attendee_registration_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.attendee_registration.revision_delete', ['attendee_registration' => $attendee_registration->id(), 'attendee_registration_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['attendee_registration_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
