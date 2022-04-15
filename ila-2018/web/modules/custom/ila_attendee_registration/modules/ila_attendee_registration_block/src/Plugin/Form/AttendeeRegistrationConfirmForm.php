<?php

namespace Drupal\ila_attendee_registration_block\Plugin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Path\PathValidatorInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Register confirmation form.
 */
class AttendeeRegistrationConfirmForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'attendee_registration_confirm';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'ila_attendee_registration_block.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    /** @var \Drupal\Core\Config\ImmutableConfig $config */
    $config = $this->config('ila_attendee_registration_block.settings');

    $form['confirmed'] = [
      '#type' => 'checkbox',
      '#title'=> t('I am confirming the binding registration for the conference as mentioned above.'),
      '#required'=> TRUE,
      '#default_value' => '',//$config->get('confirmed')
    ];

    $form[] = array(
      '#markup'  => $this->generateLinkEditData(),
      '#cache' => array(
        'max-age' => 0,
      ),
      // '#attributes' => array(
	    // 'class' => 'edit-data-link',
      // ),
    );

    $form['actions']['submit']['#value'] = t('Send');
    $form['actions']['submit']['#weight'] = 2;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  private function generateLinkEditData() {

    $conference_id = '';
    $uri_segments = explode('/', \Drupal::service('path.current')->getPath());
    if (intval($uri_segments[2]) && intval($uri_segments[2]) !=0 ) {
      $conference_id = intval($uri_segments[2]);
    }

    $options = [
      'attributes' => array(
        'class' => 'link-edit-data'
      ),
    ];
    $host = \Drupal::request()->getSchemeAndHttpHost();
    $url = $host . Url::fromRoute('ila_attendee_registration_block.register', ['node_id' => $conference_id])->toString();

    $link='';
    if (isset($url) && $url) {
      $link = Link::fromTextAndUrl($this->t('Edit data'), Url::fromUri($url, $options));
    }

    return $link;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('ila_attendee_registration_block.settings')
      ->set('confirmed', $form_state->getValue('confirmed'))
      ->save();
  }
}
