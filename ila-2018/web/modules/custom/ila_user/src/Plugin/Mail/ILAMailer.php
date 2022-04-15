<?php

namespace Drupal\ila_user\Plugin\Mail;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Markup;
use Drupal\Core\Site\Settings;
use Drupal\swiftmailer\Plugin\Mail\SwiftMailer;

/**
 * Provides a 'ILA Mailer' plugin to send emails.
 *
 * @Mail(
 *   id = "ila_mailer",
 *   label = @Translation("ILA Mailer"),
 *   description = @Translation("ILA Mailer Plugin.")
 * )
 * {@inheritDoc}
 */
class ILAMailer extends SwiftMailer {

  /**
   * {@inheritdoc}
   */
  protected function massageMessageBody(array &$message, $is_html=TRUE) {
    $line_endings = Settings::get('mail_line_endings', PHP_EOL);

    $message['body'] = Markup::create(implode($line_endings, array_map(function ($body) {
      // If the field contains no html tags we can assume newlines will need be converted to <br>
      if (strlen(strip_tags($body)) === strlen($body)) {
        $body = str_replace("\r", '', $body);
        $body = str_replace("\n", '<br>', $body);
      }

      return check_markup($body, 'full_html');
    }, $message['body'])));

    return $message;
  }
}
