<?php

namespace Drupal\ccei_indicators\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render each row as a Detail/Summary.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "ccei_indicators_details_summary",
 *   title = @Translation("CCEI Indicators Details/Summary"),
 *   help = @Translation("Displays rows in a Details/Summary."),
 *   theme = "ccei_indicators_details_summary",
 *   theme_file = "../ccei_indicators.theme.inc",
 *   display_types = {"normal"}
 * )
 */
class CceiIndicatorsDetailsSummary extends StylePluginBase {
  /**
   * Does the style plugin for itself support to add fields to it's output.
   *
   * @var bool
   */
  protected $usesFields = TRUE;

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Definition.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['summary_field'] = ['default' => NULL];

    return $options;
  }

  /**
   * Render the given style.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    if (isset($form['grouping'])) {
      unset($form['grouping']);

      $form['summary_field'] = [
        '#type' => 'select',
        '#title' => $this->t('Summary field'),
        '#options' => $this->displayHandler->getFieldLabels(TRUE),
        '#required' => TRUE,
        '#default_value' => $this->options['summary_field'],
        '#description' => $this->t('Select the field that will be used as the summary.'),
      ];
    }
  }

}
