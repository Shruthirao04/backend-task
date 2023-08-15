<?php

namespace Drupal\custom_task\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for custom task routes.
 */
class CustomTaskController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $result = \Drupal::database()->select('custom_task_example', 'table')
      ->fields('table')
      ->execute();
    $rows = [];
    foreach ($result as $row) {
      $rows[] = [
        'id' => $row->id,
        'firstname' => $row->firstname,
        'lastname' => $row->lastname,
        'email' => $row->email,
        'phone' => $row->phone,
        'gender' => $row->gender,
      ];
    }
    $build = [
      '#theme' => 'task_template',
      '#rows' => $rows,
    ];

    return $build;
  }

}
