<?php

namespace Drupal\ccei_indicators_entity\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Indicators entities.
 */
class IndicatorsEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
