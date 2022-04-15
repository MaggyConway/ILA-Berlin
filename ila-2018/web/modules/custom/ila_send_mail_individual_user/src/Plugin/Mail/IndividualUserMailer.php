<?php

namespace Drupal\ila_send_mail_individual_user\Plugin\Mail;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Render\Markup;
use Drupal\Core\Site\Settings;
use Drupal\swiftmailer\Plugin\Mail\SwiftMailer;
use Html2Text\Html2Text;

/**
 * Provides a 'ILA Individual User Mailer' plugin to send emails.
 *
 * @Mail(
 *   id = "ila_individual_user_mailer",
 *   label = @Translation("ILA Individual User Mailer"),
 *   description = @Translation("ILA Mailer Plugin.")
 * )
 * {@inheritDoc}
 */
class IndividualUserMailer extends SwiftMailer {

  /**
   * @param array $message
   * @return array
   */
  public function format(array $message)
  {
    $message = $this->massageMessageBody($message);

    // Get applicable format.
    $applicable_format = $this->getApplicableFormat($message);

    // Theme message if format is set to be HTML.
    if ($applicable_format == SWIFTMAILER_FORMAT_HTML) {
      $render = array(
        '#theme' => isset($message['params']['theme']) ? $message['params']['theme'] : 'ila_send_mail_individual_user',
        '#message' => $message,
      );

      $message['body'] = $this->renderer->renderRoot($render);

      if ($this->config['message']['convert_mode'] || !empty($message['params']['convert'])) {
        $converter = new Html2Text($message['body']);
        $message['plain'] = $converter->getText();
      }
    }

    // Process any images specified by 'image:' which are to be added later
    // in the process. All we do here is to alter the message so that image
    // paths are replaced with cid's. Each image gets added to the array
    // which keeps track of which images to embed in the e-mail.
    $embeddable_images = array();
    $processed_images = array();
    preg_match_all('/"image:([^"]+)"/', $message['body'], $embeddable_images);
    for ($i = 0; $i < count($embeddable_images[0]); $i++) {
      $image_id = $embeddable_images[0][$i];
      if (isset($processed_images[$image_id])) {
        continue;
      }
      $image_path = trim($embeddable_images[1][$i]);
      $image_name = basename($image_path);

      if (mb_substr($image_path, 0, 1) == '/') {
        $image_path = mb_substr($image_path, 1);
      }

      $image = new \stdClass();
      $image->uri = $image_path;
      $image->filename = $image_name;
      $image->filemime = \Drupal::service('file.mime_type.guesser')->guess($image_path);
      $image->cid = rand(0, 9999999999);
      $message['params']['images'][] = $image;
      $message['body'] = preg_replace($image_id, 'cid:' . $image->cid, $message['body']);
      $processed_images[$image_id] = 1;
    }

    return $message;
  }

  /**
   * {@inheritdoc}
   */
  public function massageMessageBody(array &$message, $is_html=TRUE) {
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

  /**
   * Returns the applicable format.
   *
   * @param array $message
   *   The message for which the applicable format is to be determined.
   *
   * @return string
   *   A string being the applicable format.
   */
  private function getApplicableFormat($message) {
    // Get the configured default format.
    $default_format = $this->config['message']['content_type'];

    // Get whether the provided format is to be respected.
    $respect_format = $this->config['message']['content_type'];

    // Check if a format has been provided particularly for this message. If
    // that is the case, then apply that format instead of the default format.
    $applicable_format = !empty($message['params']['format']) ? $message['params']['format'] : $default_format;

    // Check if the provided format is to be respected, and if a format has been
    // set through the header "Content-Type". If that is the case, the apply the
    // format provided. This will override any format which may have been set
    // through $message['params']['format'].
    if ($respect_format && !empty($message['headers']['Content-Type'])) {
      $format = $message['headers']['Content-Type'];

      if (preg_match('/.*\;/U', $format, $matches)) {
        $applicable_format = trim(substr($matches[0], 0, -1));
      }
      else {
        $applicable_format = $message['headers']['Content-Type'];
      }

    }

    return $applicable_format;

  }
}
