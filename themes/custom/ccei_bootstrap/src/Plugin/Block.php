<?php

namespace Drupal\ccei_bootstrap\Plugin\Preprocess;

use Drupal\wxt_bootstrap\Plugin\Preprocess\PreprocessBase;

/**
 * Pre-processes variables for the "block" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("block")
 */
class Block extends WxtBlockPreprocess {

  /**
   * {@inheritdoc}
   */
  public function preprocess(array &$variables, $hook, array $info) {
    parent::preprocess($variables, $hook, $info);
  }

}
