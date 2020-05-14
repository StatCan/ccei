<?php

namespace Drupal\ccei_indicators\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Plugin implementation of the 'cube_coordinates'.
 *
 * @Constraint(
 *   id = "cube_coordinates",
 *   label = @Translation("Cube coordinates", context = "Validation"),
 * )
 */
class CubeCoordinates extends Constraint {

  public $isEmpty = '%value is empty';

  public $notValidCoordinates = '%value are not valid coordinates';

  public $hasEmptyLine = 'List has empty lines';

}
