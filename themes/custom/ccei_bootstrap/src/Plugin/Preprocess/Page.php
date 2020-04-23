<?php

namespace Drupal\ccei_bootstrap\Plugin\Preprocess;

use Drupal\wxt_bootstrap\Plugin\Preprocess\Page as WxtPagePreprocess;

/**
 * Pre-processes variables for the "page" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("page")
 */
class Page extends WxtPagePreprocess {

  /**
   * {@inheritdoc}
   */
  public function preprocess(array &$variables, $hook, array $info) {

    parent::preprocess($variables, $hook, $info);
  }

}
