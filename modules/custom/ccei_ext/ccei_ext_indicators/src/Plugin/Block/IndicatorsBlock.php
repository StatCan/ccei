<?php

namespace Drupal\ccei_ext_indicators\Plugin\Block;

use Drupal\ccei_ext_indicators\CceiIndicatorsService;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'IndicatorsBlock' block.
 *
 * @Block(
 *  id = "indicators_block",
 *  admin_label = @Translation("Indicators block"),
 * )
 */
class IndicatorsBlock extends BlockBase implements ContainerFactoryPluginInterface{

  /**
   * An http client.
   *
   * @var \Drupal\ccei_ext_indicators\CceiIndicatorsService
   */
  protected $cceiIndicators;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\ccei_ext_indicators\CceiIndicatorsService $ccei_indicators
   *   A service to retrieve indicator data.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CceiIndicatorsService $ccei_indicators) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->cceiIndicators = $ccei_indicators;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('ccei_ext_indicators.indicators')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['indicators_list'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Indicators list'),
      '#default_value' => $this->configuration['indicators_list'] ?? '',
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['indicators_list'] = $form_state->getValue('indicators_list');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $response = $this->cceiIndicators->getIndicators();

    $build = [];
    $build['#theme'] = 'indicators_block';
    $build['#title'] = $this->t('Indicators');

    $build['#indicators'] = $response['indicators'];
    $build['#content'] = '';

    $build['#attached']['library'][] = 'ccei_ext_indicators/ccei-indicators';
    $build['#cache']['max-age'] = 5;

    return $build;
  }

}
