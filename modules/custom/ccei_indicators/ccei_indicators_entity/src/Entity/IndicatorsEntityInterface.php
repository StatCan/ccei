<?php

namespace Drupal\ccei_indicators_entity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Indicators entities.
 *
 * @ingroup ccei_indicators_entity
 */
interface IndicatorsEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Indicators name.
   *
   * @return string
   *   Name of the Indicators.
   */
  public function getName();

  /**
   * Sets the Indicators name.
   *
   * @param string $name
   *   The Indicators name.
   *
   * @return \Drupal\ccei_indicators_entity\Entity\IndicatorsEntityInterface
   *   The called Indicators entity.
   */
  public function setName($name);

  /**
   * Gets the Indicators creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Indicators.
   */
  public function getCreatedTime();

  /**
   * Sets the Indicators creation timestamp.
   *
   * @param int $timestamp
   *   The Indicators creation timestamp.
   *
   * @return \Drupal\ccei_indicators_entity\Entity\IndicatorsEntityInterface
   *   The called Indicators entity.
   */
  public function setCreatedTime($timestamp);

}
