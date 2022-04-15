<?php

namespace Drupal\ila_user\Plugin\Block;

use Drupal\user\Plugin\Block\UserLoginBlock as CoreLoginBlock;

/**
 * Provides a customized 'User login' block.
 *
 * @Block(
 *   id = "ila_user_login_block",
 *   admin_label = @Translation("ILA - User login"),
 *   category = @Translation("Forms")
 * )
 */
class UserLoginBlock extends CoreLoginBlock {

  public function build() {
    $build['description'] = [
      '#markup' => $this->t('Please enter your e-mail address and password to login.'),
    ];

    $build += parent::build();

    $build['user_login_form']['name']['#attributes']['placeholder'] = $this->t('E-mail');
    $build['user_login_form']['pass']['#attributes']['placeholder'] = $this->t('Password');

    $current_links = &$build['user_links']['#items'];

    $current_links['request_password']['#title'] = $this->t('Forgot password?');
    $current_links['create_account']['#title'] = $this->t('Registration', [], ['context' => 'ila_user_login']);

    $links = [];
    if (isset($current_links['create_account'], $current_links['request_password'])) {
      $links['request_password'] = $current_links['request_password'];
      $links['create_account'] = $current_links['create_account'];
    }

    $current_links = $links;

    return $build;
  }

}
