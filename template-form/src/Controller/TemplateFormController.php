<?php

namespace Drupal\template_form\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for template form routes.
 */
class TemplateFormController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $title = $this->config('template_form.settings')->get('title');
    $text = $this->config('template_form.settings')->get('text')['value'];
    $text_value = strip_tags($text);
    $color = $this->config('template_form.settings')->get('color');

    return [
      '#theme' => 'custom_template',
      '#text' => $text_value,
      '#title' => $title,
      '#color' => $color,
    ];
  }

}
