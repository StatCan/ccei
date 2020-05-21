<?php

/**
 * @file
 * Preprocessors and helper functions to make theming easier.
 */

use Drupal\Component\Utility\Html;

/**
 * Prepares variables for views accordion templates.
 *
 * Default template: ccei-indicators-details-summary.html.twig.
 *
 * @param array $vars
 *   An associative array containing:
 *   - view: A ViewExecutable object.
 *   - rows: The raw row data.
 */
function template_preprocess_ccei_indicators_details_summary(array &$vars) {
  $view = $vars['view'];
  $id = $view->storage->id() . '-' . $view->current_display;
  $vars['id'] = Html::getUniqueId('ccei-indicators-' . $id);
  $summary_field = $view->style_plugin->options['summary_field'];
  if ($summary_field) {
    foreach ($vars['rows'] as $id => $row) {
      $vars['rows'][$id] = [];
      $vars['rows'][$id]['content'] = $row;
      $vars['rows'][$id]['title'] = $view->style_plugin->getField($id, $summary_field);
    }
  }
}
