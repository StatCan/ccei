<?php

namespace Drupal\ccei_indicators\Plugin\Field\FieldFormatter;

use Drupal\ccei_indicators\CceiIndicatorsService;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
class IndicatorsFieldFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * An http client.
   *
   * @var \Drupal\ccei_indicators\CceiIndicatorsService
   */
  protected $cceiIndicators;

  /**
   * Construct a CCEI Indicators Formatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   Defines an interface for entity field definitions.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\ccei_indicators\CceiIndicatorsService $ccei_indicators
   *   A service to retrieve indicator data.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, CceiIndicatorsService $ccei_indicators) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->cceiIndicators = $ccei_indicators;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('ccei_indicators.indicators')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'display_header' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      'display_header' => [
        '#title' => $this->t('Display the indicators header'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('display_header'),
      ],
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $indicators = [];

    foreach ($items as $delta => $item) {
      $indicators[$delta] = $this->viewValue($item, $langcode);
    }

    // TODO: deal with empty $response or possible states.
    $response = $this->cceiIndicators->getIndicators($indicators);

    // Format numerical values according to locale.
    $decimalPoint = $langcode == 'fr' ? ',' : '.';
    $thousandSeparator = $langcode == 'fr' ? ' ' : ',';
    foreach ($response as $delta => $indicator) {
      $decimalCount = strlen(substr(strrchr($indicator['value'], "."), 1));
      $response[$delta]['value'] = number_format($indicator['value'], $decimalCount, $decimalPoint, $thousandSeparator);
    }

    return [
      '#theme' => 'indicators_block',
      '#title' => $this->t('Indicators'),
      '#indicators' => $response,
      '#display_header' => $this->settings['display_header'],
      '#attached' => [
        'library' => ['ccei_indicators/ccei-indicators'],
      ],
      '#cache' => [
        'contexts' => [
          'languages',
        ],
      ],
    ];
  }

  /**
   * Generate the unprocessed output of one indicator paragraph item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   * @param string $langcode
   *   The language that should be used to render the field.
   *
   * @return array
   *   A prepared but not yet processed indicators.
   */
  protected function viewValue(FieldItemInterface $item, $langcode) {
    // Exit early if this indicator is not valid.
    if (!isset($item->entity->field_indicator_title->value)) {
      // Assumes that if the title exists, everything else should.
      // TODO: implement proper error checking.
      return [];
    }

    $entity = $item->entity;
    foreach ($entity->field_indicator_sources as $index => $source) {
      $coords = $source->entity->field_coordinates->value;
      $coords = array_filter(array_map('trim', explode("\n", $coords)), function ($coord) {
        return preg_match('/^(\d+){1}(\.\d+){1,9}$/i', $coord);
      });
      $sources[] = [
        'productId' => $source->entity->field_pid->value,
        'coordinates' => $coords,
      ];
    }

    $i18nEntity = $entity->hasTranslation($langcode) ? $entity->getTranslation($langcode) : $entity;
    return [
      'title' => $i18nEntity->field_indicator_title->value,
      'units' => $i18nEntity->field_units->value,
      'calculation' => $entity->field_calculation->value,
      'preprocess' => $entity->field_preprocess->value,
      'value_format' => $entity->field_value_format->value,
      'rounding_precision' => $entity->field_rounding_precision->value,
      'sources' => $sources,
    ];
  }

}
