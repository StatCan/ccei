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

    // Load block megamenu
    $block = \Drupal\block\Entity\Block::load('views_block__ccei_megamenu_block_1');
    $variables['ccei_megamenu'] = \Drupal::entityTypeManager()
    ->getViewBuilder('block')
    ->view($block);

  }

}
