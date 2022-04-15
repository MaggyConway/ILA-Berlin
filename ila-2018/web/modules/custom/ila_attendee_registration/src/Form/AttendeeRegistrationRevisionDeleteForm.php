<?php

namespace Drupal\ila_attendee_registration\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Attendee registration revision.
 *
 * @ingroup ila_attendee_registration
 */
class AttendeeRegistrationRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The Attendee registration revision.
   *
   * @var \Drupal\ila_attendee_registration\Entity\AttendeeRegistrationInterface
   */
  protected $revision;

  /**
   * The Attendee registration storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $AttendeeRegistrationStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new AttendeeRegistrationRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(EntityStorageInterface $entity_storage, Connection $connection) {
    $this->AttendeeRegistrationStorage = $entity_storage;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity_type.manager');
    return new static(
      $entity_manager->getStorage('attendee_registration'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'attendee_registration_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the revision from %revision-date?', ['%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.attendee_registration.version_history', ['attendee_registration' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $attendee_registration_revision = NULL) {
    $this->revision = $this->AttendeeRegistrationStorage->loadRevision($attendee_registration_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->AttendeeRegistrationStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Attendee registration: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    \Drupal::messenger()->addMessage($this->t('Revision from %revision-date of Attendee registration %title has been deleted.',
      [
        '%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime()),
        '%title' => $this->revision->label()
      ]));
    $form_state->setRedirect(
      'entity.attendee_registration.canonical',
       ['attendee_registration' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {attendee_registration_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.attendee_registration.version_history',
         ['attendee_registration' => $this->revision->id()]
      );
    }
  }

}
