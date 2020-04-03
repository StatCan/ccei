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
    $indicators = &$config['indicators'];

    // Aggregate queries from all indicators in one array.
    // Create a lookup table to match queries with indicators.
    $queriesAccumulator = [];
    $queriesLookupTable = [];
    foreach ($config['indicators'] as $key => $indicator) {
      foreach ($indicator['sources'][0]['queries'] as $query) {
        $queriesAccumulator[] = $query;
        $hash = hash('adler32', $query['productId'] . $query['coordinate']);
        $queriesLookupTable[$hash] = $key;

        // Create a hashed response placeholders to maintain sort order.
        $indicators[$key]['response'][$hash] = [];
      }
    }

    // Call the API once with the list of queries.
    $responses = $this->queryCoords($config['urls']['url1'], $queriesAccumulator);

    // Redistribute API responses to their respective indicators, keeping order.
    foreach ($responses as $key => $response) {
      $hash = hash('adler32', $response['object']['productId'] . $response['object']['coordinate']);
      $indicators[$queriesLookupTable[$hash]]['response'][$hash] = $response;
    }

    // Process each indicators.
    $results = [];
    foreach ($indicators as $key => $indicator) {
      $results[] = $this->process($indicator);
    }

    // Return the list.
    return [
      'types' => $config['types'],
      'indicators' => $results,
    ];
  }

  private function queryCoords($url, $source) {
    // Guide: https://www.statcan.gc.ca/eng/developers/wds/user-guide#a12-4
    $request = $this->httpClient->post($url,
      [
        'json' => $source,
      ]);

    // TODO: Implement http response status validation.
    return Json::decode($request->getBody()->getContents());
  }

  private function process($indicator) {
    // Reset responses to numerical array.
    $responseIndicators = array_values($indicator['response']);

    $datapoints = [];
    foreach ($responseIndicators as $key => $responseDatapoint) {
      $datapoints[$key]['values'] = $responseDatapoint['object']['vectorDataPoint'];
      $datapoints[$key]['previous'] = reset($datapoints[$key]['values'])['value'];
      $datapoints[$key]['latest'] = end($datapoints[$key]['values'])['value'];
    }

    // Call specialised process function.
    list('previous' => $previous, 'latest' => $latest) = $this->{$indicator['function']}($datapoints);

    if (isset($indicator['valueformat'])) {
      $formattedValue = call_user_func_array(
        $indicator['valueformat']['function'],
        [$latest, $indicator['valueformat']['precision']]
      );
    }

    $percentChange = round(($latest - $previous) / $previous * 100, 1);

    return [
      'title' => $indicator['title'],
      'type' => $indicator['type'],
      'change' => $percentChange . '%',
      'direction' => $percentChange > 0 ? 'up' : ($percentChange == 0 ? 'nil' : 'down'),
      'value' => $formattedValue ?? $latest,
      'units' => $indicator['units'],
      'refper' => reset($datapoints[0]['values'])['refPer'],
    ];
  }

  private function processSimple($datapoints) {
    return [
      'previous' => $datapoints[0]['previous'],
      'latest' => $datapoints[0]['latest'],
    ];
  }

  private function processDivisionThousand($datapoints) {
    return [
      'previous' => $datapoints[0]['previous'] / 1000,
      'latest' => $datapoints[0]['latest'] / 1000,
    ];
  }

  private function processAverage($datapoints) {
    return [
      'previous' => $datapoints[1]['previous'] / $datapoints[0]['previous'] * 100,
      'latest' => $datapoints[1]['latest'] / $datapoints[0]['latest'] * 100,
    ];
  }

  private function processSumTimesConstant($datapoints) {
    // TODO: what to do if a datapoint failed to load/is null?
    return [
      'previous' => array_sum(array_column($datapoints, 'previous')) * 6.28981077,
      'latest' => array_sum(array_column($datapoints, 'latest')) * 6.28981077,
    ];
  }

  public function apiTest() {
    opcache_invalidate(__FILE__, true);
    $response = $this->getIndicatorsData();
    return JsonResponse::create($response);
  }

}

