<?php

namespace Drupal\ccei_ext_indicators;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class CceiIndicatorsService.
 */
class CceiIndicatorsService implements CceiIndicatorsServiceInterface {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * An http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Constructs a new CceiIndicatorsService object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   An HTTP client.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client) {
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
  }

  public function getIndicatorsDataOld() {

  $request = $this->httpClient->request('GET', 'https://www150.statcan.gc.ca/n1/en/indicators.json',
    [
      'query' => [
        'count' => 10,
      ],
    ]);

    $response = Json::decode($request->getBody()->getContents());

    return $response;
  }

  public function getIndicatorsData() {
    // Load list of indicator parameters.
    $config = $this->configFactory->get('ccei_ext_indicators.indicators')->get('');

    $results = [];
    // For each indicator call a dedicated function for their particular case.
    // Accumulate their results in a list.
    foreach ($config['indicators'] as $indicator) {
      $results[] = $this->{'process' . $indicator['id']}($config['urls'], $indicator);
    }

    // Return the list.
    return $results;
  }

  private static function packageIndicator($indicator, $result) {
    $output = [];
    $output['title'] = $indicator['title'];
    $output = array_merge($output, $result);
    $output['units'] = $indicator['units'];

    return $output;
  }

  private function queryVector($url, $query) {
    // Guide: https://www.statcan.gc.ca/eng/developers/wds/user-guide#a12-4
    $request = $this->httpClient->post($url,
      [
//        'on_stats' => function (\GuzzleHttp\TransferStats $stats) {
//          echo $stats->getEffectiveUri() . ' : ' . $stats->getTransferTime();
//        },
        'json' => [
          $query,
        ],
      ]);

    // TODO: Implement http response status validation.
    $response = Json::decode($request->getBody()->getContents());
    return $response;
  }

  public static function percentChange($previous, $latest) {
    return round(($latest - $previous) / $previous * 100, 2) . '%';
  }

  private function processElect001($urls, $indicator) {
    $response = $this->queryVector($urls[$indicator['sources'][0]['base_url']], $indicator['sources'][0]['query']);

    $values = $response[0]['object']['vectorDataPoint'];
    $previous = reset($values)['value'] / 1000;
    $latest = end($values)['value'] / 1000;

    $percentChange = self::percentChange($previous, $latest);

    $result = [
      'change' => $percentChange,
      'value' => round($latest),
    ];
    return self::packageIndicator($indicator, $result);
  }

  public function apiTest() {
    opcache_invalidate(__FILE__, true);
    $response = $this->getIndicatorsData();
    return JsonResponse::create($response);
  }

}

