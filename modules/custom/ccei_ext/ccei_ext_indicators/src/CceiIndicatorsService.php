<?php

namespace Drupal\ccei_ext_indicators;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use NXP\MathExecutor;
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
   * A mathematical expression parser.
   *
   * @var \NXP\MathExecutor
   */
  protected $executor;

  /**
   * Constructs a new CceiIndicatorsService object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   An HTTP client.
   * @param \NXP\MathExecutor $executor
   *   A mathematical expression parser.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client, MathExecutor $executor) {
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
    $this->executor = $executor;
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

  public function getIndicatorTypes() {
    return $this->configFactory->get('ccei_ext_indicators.indicators')->get('types');
  }

  public function getIndicatorsData($types = []) {
    // Load list of indicator parameters.
    $config = $this->configFactory->get('ccei_ext_indicators.indicators')->get('');

    if (empty($types)) {
      $types = array_keys($config['types']);
    }

    $filteredTypes = array_filter($config['types'], function ($key) use ($types) {
      return in_array($key, $types);
    }, ARRAY_FILTER_USE_KEY);

    $indicators = array_values(array_filter($config['indicators'], function ($var) use ($types) {
      return in_array($var['type'], $types);
    }));

    // Aggregate queries from all indicators in one array.
    // Create a lookup table to match queries with indicators.
    $queriesAccumulator = [];
    $queriesLookupTable = [];
    foreach ($indicators as $indicatorKey => $indicator) {
      foreach ($indicator['sources'] as $sourceKey => $source) {
        foreach ($source['coordinates'] as $coordinate) {
          // Pad incomplete coordinates to 10 numbers.
          $fullCoordinate = implode('.', array_pad(explode('.', $coordinate), 10, 0));
          // Format queries to API specs.
          $queriesAccumulator[] = [
            'productId' => $source['productId'],
            'coordinate' => $fullCoordinate,
            'latestN' => $source['latestN'],
          ];

          // The API response doesn't maintain query order.
          // Create a indexed response placeholders and a lookup table to
          // maintain sort order.
          // The index is based on the sourceId + coordinate due to the limited
          // information returned. There is a collision possibility if multiple
          // indicators use the same combination for some of its queries.
          // TODO: find a better way, for example creating a global standalone
          //   indexed list and having indicators reference it instead.
          $index = $source['productId'] . '-' . $fullCoordinate;
          $queriesLookupTable[$index] = [
            'source' => $sourceKey,
            'indicator' => $indicatorKey,
          ];
          $indicators[$indicatorKey]['response'][$sourceKey][$index] = [];
        }
      }
    }

    // Call the API once with the list of queries.
    $responses = $this->queryCoords($config['urls']['url1'], $queriesAccumulator);

    // Redistribute API responses to their respective indicators, keeping order.
    foreach ($responses as $key => $response) {
      $index = $response['object']['productId'] . '-' . $response['object']['coordinate'];
      $sourceKey = $queriesLookupTable[$index]['source'];
      $indicatorKey = $queriesLookupTable[$index]['indicator'];
      $indicators[$indicatorKey]['response'][$sourceKey][$index] = $response;
    }

    // Process each indicators.
    $results = [];
    foreach ($indicators as $key => $indicator) {
      $results[] = $this->process($indicator);
    }

    // Return the list.
    return [
      'types' => $filteredTypes,
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
    $datapoints = [];
    foreach ($indicator['response'] as $sourceKey => $source) {
      // Reset responses to numerical array.
      $responseIndicators = array_values($source);
      foreach ($responseIndicators as $key => $responseDatapoint) {
        $datapoints['values'][$sourceKey][$key] = $responseDatapoint['object']['vectorDataPoint'];
        $datapoints['previous'][$sourceKey][$key] = reset($datapoints['values'][$sourceKey][$key])['value'];
        $datapoints['latest'][$sourceKey][$key] = end($datapoints['values'][$sourceKey][$key])['value'];
      }
    }

    // Calculate datapoints with submitted formula.
    $previous = $this->calculate($indicator, $datapoints['previous']);
    $latest = $this->calculate($indicator, $datapoints['latest']);

    // Format the indicator value.
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
      'refper' => reset($datapoints['values'][0][0])['refPer'],
    ];
  }

  private function calculate($indicator, $datapoints) {
    if (!empty($indicator['calculation'])) {
      $variables = [];
      foreach ($datapoints as $sourceKey => $sourceDatapoints) {
        foreach ($sourceDatapoints as $key => $datapoint) {
          $variables['src' . $sourceKey . 'var' . $key] = $datapoint ?? 0;
        }
        if (!empty($indicator['preprocess']) && $indicator['preprocess'] === 'sum') {
          $variables['src' . $sourceKey . 'sum'] = array_sum($sourceDatapoints);
        }
      }

      $this->executor->setVars($variables, TRUE);
      return $this->executor->execute($indicator['calculation']);
    }
    else {
      // Assume first source and first datapoint or default to 0.
      return $datapoints[0][0] ?? 0;
    }
  }

  public function apiTest() {
    opcache_invalidate(__FILE__, true);
    $response = $this->getIndicatorsData();
    return JsonResponse::create($response);
  }

}

