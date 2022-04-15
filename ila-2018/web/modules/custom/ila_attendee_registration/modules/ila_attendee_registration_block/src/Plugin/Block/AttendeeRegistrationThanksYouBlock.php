<?php

namespace Drupal\ila_attendee_registration_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
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
 * Provides a block for attendee registration  confirm forms.
 *
 * @Block(
 *   id = "attendee_registration_complete",
 *   admin_label = @Translation("Attendee registration complete"),
 *   provider = "node",
 *   category = @Translation("Forms")
 * )
 */
class AttendeeRegistrationThanksYouBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    $form = [];

    $config = $config = \Drupal::config('ila_attendee_registration_block.settings');
    $entity = $this->findUserNodes(\Drupal::currentUser());
    $full_name =  t($entity->get('field_ar_salutation')->getValue()[0]['value']) . ' ' . $entity->get('field_ar_first_name')->getValue()[0]['value'] . ' ' . $entity->get('field_ar_last_name')->getValue()[0]['value'];
    $text = t($config->get('message.thanks'), array('@user_name' => $full_name));
    $form['#markup'] = $text;

    return $form;
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

    $entity_attendee = $this->entityTypeManager->getListBuilder('attendee_registration')->getStorage()->loadByProperties($values);

    return end($entity_attendee);
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
