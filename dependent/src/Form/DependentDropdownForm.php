<?php

namespace Drupal\dependent\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Class for dropdown form.
 */
class DependentDropdownForm extends FormBase {

  /**
   * Implements getFomId function.
   */
  public function getFormId() {
    return 'item_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $selected_item_id = $form_state->getValue("item");
    $selected_model_id = $form_state->getValue("model");
    $form['item'] = [
      '#type' => 'select',
      '#title' => $this->t('Item'),
      '#options' => $this->getItemOptions(),
      '#empty_option' => $this->t('- Select -'),
      '#ajax' => [
        'callback' => [$this, 'ajaxModelDropdownCallback'],
        'wrapper' => 'model-dropdown-wrapper',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Loading...'),
        ],
      ],
    ];

    $form['model'] = [
      '#type' => 'select',
      '#title' => $this->t('Model'),
      '#options' => $this->getModelOptions($selected_item_id),
      '#empty_option' => $this->t('- Select -'),
      '#prefix' => '<div id="model-dropdown-wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => [$this, 'ajaxColorDropdownCallback'],
        'wrapper' => 'color-dropdown-wrapper',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Loading...'),
        ],
      ],
    ];

    $form['color'] = [
      '#type' => 'select',
      '#title' => $this->t('Color'),
      '#options' => $this->getColorByModel($selected_model_id),
      '#prefix' => '<div id="color-dropdown-wrapper">',
      '#suffix' => '</div>',
      '#empty_option' => $this->t('- Select -'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Submit form.
    // Value will be stored.
    $trigger = (string) $form_state->getTriggeringElement()['#value'];
    if ($trigger != 'submit') {
      // If value is not submitted it will be triggered.
      $form_state->setRebuild();
    }
  }

  /**
   * Implements ajaxModelDropdownCallback function.
   */
  public function ajaxModelDropdownCallback(array &$form, FormStateInterface $form_state) {
    return $form['model'];
  }

  /**
   * Implements ajaxColorDropdownCallback function.
   */
  public function ajaxColorDropdownCallback(array &$form, FormStateInterface $form_state) {
    return $form['color'];
  }

  /**
   * Implements getItemOptions function.
   */
  private function getItemOptions() {
    $query = Database::getConnection()->select('item', 'i');
    $query->fields('i', ['id', 'name']);
    $result = $query->execute();
    $item = [];

    foreach ($result as $row) {
      $item[$row->id] = $row->name;
    }

    return $item;
  }

  /**
   * Implements getModelOptions function.
   */
  private function getModelOptions($selected_item_id) {

    $query = Database::getConnection()->select('model', 's');
    $query->fields('s', ['id', 'name']);
    $query->condition('s.item_id', $selected_item_id);
    $result = $query->execute();

    $model = [];
    foreach ($result as $row) {
      $model[$row->id] = $row->name;
    }
    return $model;
  }

  /**
   * Implements getColorByModel function.
   */
  public function getColorByModel($selected_model_id) {
    $query = Database::getConnection()->select('color', 'c');
    $query->fields('c', ['id', 'name']);
    $query->condition('c.model_id', $selected_model_id);
    $result = $query->execute();

    $color = [];
    foreach ($result as $row) {
      $color[$row->id] = $row->name;
    }

    return $color;
  }

}
