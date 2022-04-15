<?php

namespace Drupal\ila_attendee_registration_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\NodeType;


use Drupal\Component\Utility\Xss;

use Drupal\formblock\Plugin\Block\NodeFormBlock;


/**
 * Provides a block for exhibitor registration forms.
 *
 * @Block(
 *   id = "attendee_registration",
 *   admin_label = @Translation("Attendee registration"),
 *   provider = "node",
 *   category = @Translation("Forms")
 * )
 */
class AttendeeRegistrationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity form builder.
   *
   * @var \Drupal\Core\Entity\EntityFormBuilderInterface.
   */
  protected $entityFormBuilder;

  /**
   * Constructs a new NodeFormBlock plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity manager.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entityFormBuilder
   *   The entity form builder.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, EntityFormBuilderInterface $entityFormBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->setConfiguration($configuration);

    $this->entityTypeManager = $entityTypeManager;
    $this->entityFormBuilder = $entityFormBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity.form_builder')
    );
  }

  /**
   * Overrides \Drupal\block\BlockBase::settings().
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockForm().
   */
  public function blockForm($form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockSubmit().
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
  }

  /**
   * Implements \Drupal\block\BlockBase::build().
   */
  public function build() {
    $build = [];

    $entity_type = 'attendee_registration';
    $session = \Drupal::request()->getSession();
    $conf_id = $session->get('registration_id', 0);
    $current_user = \Drupal::currentUser();

    if (isset($conf_id)) {
      $entity = \Drupal::entityTypeManager()->getStorage('attendee_registration')->load((int) $conf_id);
    }
    if ($current_user->id() !== 0) {
      $entity = $this->findUserNodes(\Drupal::currentUser());
    }

    if ($entity && count($entity) >= 1) {
      if ($current_user->id() !== 0) {
        $entity = end($entity);
      }
      else {
        $entity = \Drupal::entityTypeManager()->getStorage('attendee_registration')->load((int) $conf_id);
      }
    }
    else {
      $entity = $this->entityTypeManager->getStorage('attendee_registration')->create([
        'type' => $entity_type,
      ]);
    }

    $build['form'] = $this->disableBaseFields($this->entityFormBuilder->getForm($entity));

    if ($build['form']['field_ar_registration_confirmed']['widget']['value']['#default_value'] == TRUE) {
      $config = $config = \Drupal::config('ila_attendee_registration_block.settings');
      return ['#markup' => t($config->get('message.register'))];
    }

    $build['form']['actions']['delete']['#access'] = FALSE;

    $build['#markup'] = '<h2 class="attendee-registration-title">' . t("For your registration please fill in the following form.</br>With * marked fields are required") . '</h2>';

    return $build;
  }

  /**
   * Returns the node object for associated user.
   */
  protected function findUserNodes(AccountInterface $account) {
    $conference_id = '';
    $uri_segments = explode('/', \Drupal::service('path.current')->getPath());
    if (intval($uri_segments[2]) && intval($uri_segments[2]) !=0 ) {
      $conference_id = intval($uri_segments[2]);
    }
    $values = [
      'user_id' => $account->id(),
      'field_ar_conference' => $conference_id,
    ];

    return $this->entityTypeManager->getListBuilder('attendee_registration')->getStorage()->loadByProperties($values);
  }

  /**
   * Disable base fields.
   */
  protected function disableBaseFields($form) {
    $fields = [
      'revision_log_message',
      'new_revision',
      'langcode',
      'user_id',
    ];

    foreach ($fields as $field) {
      $form[$field]['#access'] = FALSE;
    }

    return $form;
  }


  /**
   * {@inheritdoc}
   */
//  public function blockAccess(AccountInterface $account) {
//    $access_control_handler = $this->entityTypeManager->getAccessControlHandler('node');
//
//    // NodeAccessControlHandler::createAccess() adds user.permissions
//    // as a cache context to the returned AccessResult.
//    /* @var $result \Drupal\Core\Access\AccessResult */
//    $result = $access_control_handler->createAccess($this->configuration['type'], $account, [], TRUE);
//
//    // Add the node type as a cache dependency.
//    /* $result->addCacheTags($node_type->getCacheTags()); */
//
//    return $result;
//  }

}
