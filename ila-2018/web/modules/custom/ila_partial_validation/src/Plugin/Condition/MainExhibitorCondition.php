<?php

namespace Drupal\ila_partial_validation\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * Provides a 'Main Exhibitor' condition.
 *
 * @Condition(
 *   id = "main_exhibitor",
 *   label = @Translation("Main Exhibitor"),
 * )
 */
class MainExhibitorCondition extends ConditionPluginBase implements ContainerFactoryPluginInterface {

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
    if (!$node = $this->getLatestMainExhibitorNode(\Drupal::currentUser())) {
      if ($this->isRequestPage()) {
        \Drupal::messenger()->addMessage($this->t('To access this page, please fill in the Main Exhibitor form'), 'error');
      }

      return FALSE;
    }

    $groups = field_group_info_groups('node', 'main_exhibitor', 'form', 'default');
    $groups = $this->processGroupsArray($groups);

    // Filter by #required.
    foreach ($groups as &$group) {
      foreach ($group as $key => $field_name) {
        if (!$node->get($field_name)->getFieldDefinition()->isRequired()) {
          unset($group[$key]);
        }
      }
    }

    // Get validation array and remove 'empty' validators from it. We don't
    // want groups to have not filled, but required fields.
    $validators = $this->removeValidators(_ila_partial_validation_get_validators(), 'empty');
    $errors = ila_partial_validation_process_validators($groups, $validators, $node);

    if (empty($errors)) {
      return TRUE;
    }

    // Check whether to show the message with the list of empty fields or not.
    if ($this->isRequestPage()) {
      $empty_fields = array_keys($errors);
      foreach ($empty_fields as &$empty_field) {
        $empty_field = $node->get($empty_field)->getFieldDefinition()->getLabel();
      }
      \Drupal::messenger()->addMessage($this->t('To access this page, please fill out the following fields on the Main Exhibitor page: @field_labels', ['@field_labels' => implode(', ', $empty_fields)]), 'error');
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    if ($this->isNegated()) {
      return $this->t('Main Exhibitor form must be NOT filled by logged in user');
    }

    return $this->t('Main Exhibitor form must be filled by logged in user');
  }

  /**
   * Checks if current page is request page.
   */
  private function isRequestPage() {
    $request_pages = [
      'exhibitor-zone/requests',
      'exhibitor-zone/request_a',
      'exhibitor-zone/request_b',
      'exhibitor-zone/request_c',
      'exhibitor-zone/request_d',
      'exhibitor-zone/request_e',
      'exhibitor-zone/request_f',
      'exhibitor-zone/request_fb1',
      'exhibitor-zone/co-exhibitor',
    ];

    $current_url = Url::fromRoute('<current>')->getInternalPath();

    return array_search($current_url, $request_pages) !== FALSE;
  }

  /**
   * Returns the latest Main Exhibitor node object for the given user.
   */
  protected function getLatestMainExhibitorNode(AccountInterface $account) {
    $values = [
      'type' => 'main_exhibitor',
      'uid' => $account->id(),
    ];

    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties($values);

    return end($nodes);
  }

  /**
   * Remove validators of the specified type from the given validation array.
   */
  protected function removeValidators(array $validators, string $validator_type) {
    foreach ($validators as &$group) {
      // For not nested validators.
      $operator = '';
      if (isset($group['validators']['or'])) {
        $operator = 'or';
      }
      elseif (isset($group['validators']['and'])) {
        $operator = 'and';
      }

      foreach ($group['validators'][$operator] as $condition_key => $condition) {
        if (isset($condition['validator']) && $condition['validator'] === $validator_type) {
          unset($group['validators'][$operator][$condition_key]);
        }
      }

      if (empty($group['validators'][$operator])) {
        unset($group['validators'][$operator]);
      }
    }

    return $validators;
  }

  /**
   * Returns groups list prepared for ila_partial_validation_process_validators.
   */
  protected function processGroupsArray(array $groups) {
    $output = [];

    foreach ($groups as $key => $group) {
      $output[$key] = $this->getGroupElements($key, $groups);
    }

    return $output;
  }

  /**
   * Returns a list of elements for a given group.
   */
  protected function getGroupElements(string $group_name, array $groups) {
    $output = [];
    $group_elements = $groups[$group_name]->children;
    foreach ($group_elements as $element_name) {
      if (isset($groups[$element_name])) {
        $output = array_merge($output, $this->getGroupElements($element_name, $groups));

        continue;
      }

      $output[] = $element_name;
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = parent::getCacheContexts();
    $contexts[] = 'route.menu_active_trails:exhibitor-registration-menu';

    return $contexts;
  }

}
