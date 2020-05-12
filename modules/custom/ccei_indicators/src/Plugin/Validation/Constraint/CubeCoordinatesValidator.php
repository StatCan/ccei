<?php

namespace Drupal\ccei_indicators\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the CubeCoordinates constraint.
 */
class CubeCoordinatesValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    foreach ($items as $item) {
      if (empty($item->value)) {
        $this->context->addViolation($constraint->isEmpty, ['%value' => $item->value]);
      }

      $list = array_map('trim', explode("\n", $item->value));
      $filtered = array_filter($list, function ($coord) {
        return preg_match('/^(\d+){1}(\.\d+){1,9}$/i', $coord);
      });
      $coordinateDiff = array_diff($list, $filtered);

      if (!empty($coordinateDiff)) {
        $contains_empty = count($coordinateDiff) != count(array_filter($coordinateDiff, "strlen"));
        if ($contains_empty) {
          $this->context->addViolation($constraint->hasEmptyLine, []);
        }
        $this->context->addViolation($constraint->notValidCoordinates, ['%value' => implode(", ", $coordinateDiff)]);
      }
    }
  }

}
