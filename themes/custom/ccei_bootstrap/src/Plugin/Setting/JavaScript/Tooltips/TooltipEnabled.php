<?php
/**
 * @file
 * Contains \Drupal\bootstrap\Plugin\Setting\JavaScript\Tooltips\TooltipEnabled.
 */

namespace Drupal\ccei_bootstrap\Plugin\Setting\JavaScript\Tooltips;

use Drupal\bootstrap\Annotation\BootstrapSetting;
use Drupal\bootstrap\Plugin\Setting\JavaScript\Tooltips\TooltipEnabled as BootstrapTooltipEnabled;
use Drupal\Core\Annotation\Translation;

/**
 * The "tooltip_enabled" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "tooltip_enabled",
 *   type = "checkbox",
 *   title = @Translation("Enable Bootstrap Tooltips"),
 *   description = @Translation("Elements that have the <code>data-toggle=&quote;tooltip&quote;</code> attribute set will automatically initialize the tooltip upon page load. <strong class='error text-error'>WARNING: This feature can sometimes impact performance. Disable if pages appear to &quote;hang&quote; after initial load.</strong>"),
 *   defaultValue = 0,
 *   weight = -1,
 *   groups = {
 *     "javascript" = @Translation("JavaScript"),
 *     "tooltips" = @Translation("Tooltips"),
 *   },
 * )
 */
class TooltipEnabled extends BootstrapTooltipEnabled {}
