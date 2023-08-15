<?php

namespace Drupal\entity_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class extension.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a CustomAccessControlSettingsForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'entity_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['entity_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('entity_form.settings');
    $role_storage = $this->entityTypeManager->getStorage('user_role');
    $roles = $role_storage->loadMultiple();
    $role_options = [];
    foreach ($roles as $role) {
      $role_options[$role->id()] = $role->label();
    }

    $content_type_storage = $this->entityTypeManager->getStorage('node_type');
    $content_types = $content_type_storage->loadMultiple();
    $content_type_options = [];
    foreach ($content_types as $content_type) {
      $content_type_options[$content_type->id()] = $content_type->label();
    }

    $form['roles'] = [
      '#type' => 'checkboxes',
      '#title' => t('Select Roles'),
      '#options' => $role_options,
      '#default_value' => $config->get('roles', []),
    ];

    $form['content_types'] = [
      '#type' => 'checkboxes',
      '#title' => t('Select Content Types'),
      '#options' => $content_type_options,
      '#default_value' => $config->get('content_types'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    // Retrieve the mutable configuration object.
    $config = $this->configFactory->getEditable('entity_form.settings');

    // Set the roles and content types in the configuration.
    $config->set('roles', $values['roles']);
    $config->set('content_types', $values['content_types']);
    $config->save();

    // Call the parent submitForm() to handle form submission.
    parent::submitForm($form, $form_state);
  }

}
