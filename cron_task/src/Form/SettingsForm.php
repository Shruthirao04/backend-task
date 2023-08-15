<?php

namespace Drupal\cron_task\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure cron task settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cron_task_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cron_task.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('subject'),
      '#default_value' => $this->config('cron_task.settings')->get('subject'),
    ];
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('text'),
      '#default_value' => $this->config('cron_task.settings')->get('text'),
    ];
    if (\Drupal::moduleHandler()->moduleExists('token')) {
      $form['tokens'] = [
        '#title' => $this->t('Tokens'),
        '#type' => 'container',
      ];
      $form['tokens']['help'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => [
          'node',
        ],
            // '#token_types' => 'all'
        '#global_types' => FALSE,
        '#dialog' => TRUE,
      ];
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('cron_task.settings')
      ->set('subject', $form_state->getValue('subject'))
      ->set('text', $form_state->getValue('text'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
