<?php

namespace Drupal\custom_task\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class extension.
 */
class CustomForm extends FormBase {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * CustomForm constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   */
  public function __construct(MessengerInterface $messenger, Connection $database) {
    $this->messenger = $messenger;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      // Load the service required to construct this class.
      $container->get('messenger'),
      $container->get('database')
    );
  }

  /**
   * Implements function getFormId.
   */
  public function getFormId() {
    return 'custom_form_details';
  }

  /**
   * Implements build form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['firstname'] = [
      '#type' => 'textfield',
      '#title' => 'Firstname',
      '#required' => TRUE,
      '#placeholder' => 'firstname',
    ];
    $form['lastname'] = [
      '#type' => 'textfield',
      '#title' => 'Lastname',
      '#required' => TRUE,
      '#placeholder' => 'lastname',
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => 'Email',
      '#required' => TRUE,
      '#placeholder' => 'email',
    ];
    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => 'phone',
      '#required' => TRUE,
      '#placeholder' => 'phone',
    ];
    $form['gender'] = [
      '#type' => 'textfield',
      '#title' => 'gender',
      '#required' => TRUE,
      '#placeholder' => 'gender',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
    ];
    return $form;
  }

  /**
   * Implements submit function.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger->addMessage("Details submitted");
    $this->database->insert("custom_task_example")->fields([
      'firstname' => $form_state->getValue("firstname"),
      'lastname' => $form_state->getValue("lastname"),
      'email' => $form_state->getValue("email"),
      'phone' => $form_state->getValue("phone"),
      'gender' => $form_state->getValue("gender"),
    ])->execute();
  }

}
