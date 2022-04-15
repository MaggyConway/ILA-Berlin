<?php

namespace Drupal\menu_export\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Entity\Menu;
use Drupal\menu_link_content\Entity\MenuLinkContent;

/**
 * Configure Menu Export settings.
 */
class MenuImportForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_import_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'menu_export.settings',
      'menu_export.export_data'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Menu Links'),
    ];
    return $form;
  }

  /**
   * Custom form validation for email.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    return parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $invalidMenus = [];
    $menus = $this->config('menu_export.export_data')->get();
    foreach ($menus as $key => $menu) {
      $menu_name = $menu['menu_name']['value'];
      if (!Menu::load($menu_name)) {
        $invalidMenus[] = $menu_name;
        continue;
      }
      if ($menu['id']['value'] == 32) {
        kint($menu);
      }

      $menuLinkEntity = \Drupal::entityQuery('menu_link_content')
        ->condition('menu_name', $menu_name)
        ->condition('uuid', $menu['uuid']['value'])
        ->execute();
      if (!$menuLinkEntity) {
        $menuLinkEntity = MenuLinkContent::create();
      }else{
        $menuLinkEntity = MenuLinkContent::load(reset($menuLinkEntity));
      }
      foreach ($menu as $kkey => $items) {
        if ($kkey != 'id') {
          $menuLinkEntity->set($kkey, $items);
        }
      }
      $menuLinkEntity->save();
      unset($menuLinkEntity);

    }
    if(count($invalidMenus)){
      \Drupal::messenger()->addMessage($this->t('Menu(s) @menus not found',['@menus'=>implode(',',$invalidMenus)]),'error');
    }else{
      \Drupal::messenger()->addMessage($this->t('Menu(s) imported successfully'),'success');
    }
  }

}
