<?php

namespace Drupal\ccei_indicators\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'indicators_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "indicators_field_formatter",
 *   label = @Translation("Indicators field formatter"),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
class IndicatorsFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => print_r($this->viewValue($item), TRUE)];
    }

    return $elements;
  }

  /**
   * Generate the unprocessed output of one indicator paragraph item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return array
   *   A prepared but not yet processed indicators.
   */
  protected function viewValue(FieldItemInterface $item) {
    // Exit early if this indicator is nto valid.
    if (!isset($item->entity->field_indicator_title->value)) {
      // Assumes that if the title exists, everything else should.
      // TODO: implement proper error checking.
      return [];
    }

    foreach ($item->entity->field_indicator_sources as $index => $source) {
      $coords = $source->entity->field_coordinates->value;
      $coords = array_filter(array_map('trim', explode("\n", $coords)), function ($coord) {
        return preg_match('/^(\d+){1}(\.\d+){1,9}$/i', $coord);
      });
      $sources[] = [
        'productId' => $source->entity->field_pid->value,
        'coordinates' => $coords,
      ];
    }

    return [
      'title' => $item->entity->field_indicator_title->value,
      'units' => $item->entity->field_units->value,
      'calculation' => $item->entity->field_calculation->value,
      'preprocess' => $item->entity->field_preprocess->value,
      'value_format' => $item->entity->field_value_format->value,
      'rounding_precision' => $item->entity->field_rounding_precision->value,
      'sources' => $sources,
    ];
  }

}
