<?php

namespace Drupal\ila_exhibitor_registration\Plugin\Block;

use Drupal\Core\Session\AccountInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\Entity\NodeType;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\formblock\Plugin\Block\NodeFormBlock;

/**
 * Provides a block for exhibitor registration forms.
 *
 * @Block(
 *   id = "exhibitor_registration",
 *   admin_label = @Translation("Exhibitor registration"),
 *   provider = "node",
 *   category = @Translation("Forms")
 * )
 */
class ExhibitorRegistrationBlock extends NodeFormBlock implements ContainerFactoryPluginInterface {

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
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The Entity Display Repository.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, EntityFormBuilderInterface $entityFormBuilder, $entity_display_repository) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entityTypeManager, $entityFormBuilder, $entity_display_repository);
    $this->setConfiguration($configuration);

    $this->entityTypeManager = $entityTypeManager;
    $this->entityFormBuilder = $entityFormBuilder;
  }

  /**
   * Implements \Drupal\block\BlockBase::build().
   */
  public function build() {
    $build = [];

    $node_type = NodeType::load($this->configuration['type']);

    if ($this->configuration['show_help']) {
      $build['help'] = ['#markup' => !empty($node_type->getHelp()) ? '<p>' . Xss::filterAdmin($node_type->getHelp()) . '</p>' : ''];
    }

    $node = FALSE;
    if ($this->configuration['type'] == 'main_exhibitor') {
      // Check if there is already a Main Exhibitor node associated with current
      // user.
      $node = $this->findUserNodes(\Drupal::currentUser());
    }

    if ($node && count($node) >= 1) {
      $node = end($node);
    }
    else {
      $node = $this->entityTypeManager->getStorage('node')->create([
        'type' => $node_type->id(),
      ]);
    }

    $build['form'] = $this->entityFormBuilder->getForm($node);

    // Hardcoded.
    if (isset($build['form']['advanced'])) {
      unset($build['form']['advanced']);
    }

    return $build;
  }

  /**
   * Returns the node object for associated user.
   */
  protected function findUserNodes(AccountInterface $account) {
    $values = [
      'type' => $this->configuration['type'],
      'uid' => $account->id(),
    ];

    return $this->entityTypeManager->getListBuilder('node')->getStorage()->loadByProperties($values);
  }

}
