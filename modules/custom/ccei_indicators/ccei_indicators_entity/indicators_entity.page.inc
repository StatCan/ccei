<?php

/**
 * @file
 * Contains indicators_entity.page.inc.
 *
 * Page callback for Indicators entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Indicators templates.
 *
 * Default template: indicators_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_indicators_entity(array &$variables) {
  // Fetch IndicatorsEntity Entity Object.
  $indicators_entity = $variables['elements']['#indicators_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
