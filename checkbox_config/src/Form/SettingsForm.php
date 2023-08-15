<?php

namespace Drupal\checkbox_config\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure checkbox config settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'checkbox_config_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['checkbox_config.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $entity = \Drupal::config('checkbox_config.settings')->get('vocabulary');
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('title'),
      '#default_value' => $this->config('checkbox_config.settings')->get('title'),
    ];
    $form['checkbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('advanced'),
      '#default_value' => $this->config('checkbox_config.settings')->get('checkbox'),
    ];

    $entity_tag = NULL;
    if (is_numeric($entity)) {
      $entity_tag = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($entity);
    }
    $form['vocabulary'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('tags'),
      '#target_type' => 'taxonomy_term',
      '#default_value' => $entity_tag,
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('checkbox_config.settings')
      ->set('title', $form_state->getValue('title'))
      ->set('checkbox', $form_state->getValue('checkbox'))
      ->set('vocabulary', $form_state->getValue('vocabulary'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
