<?php

namespace Drupal\ccei_bootstrap\Plugin\Setting\Accessibility\SkipNav;

use Drupal\bootstrap\Annotation\BootstrapSetting;
use Drupal\bootstrap\Plugin\Setting\SettingBase;
use Drupal\bootstrap\Utility\Element;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormStateInterface;

/**
 * The "wxt_skip_link_secondary_text" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "wxt_skip_link_secondary_text",
 *   type = "textfield",
 *   title = @Translation("Text for the secondary ""skip link"""),
 *   defaultValue = @Translation("Skip to footer"),
 *   description = @Translation("For example: <em>Jump to navigation</em>, <em>Skip to content</em>."),
 *   groups = {
 *     "accessibility" = @Translation("Accessibility"),
 *     "skip_nav" = @Translation("Skip Navigation"),
 *   }
 * )
 */
class SkipLinkSecondaryText extends SettingBase {

  /**
   * {@inheritdoc}
   */
  public function alterFormElement(Element $form, FormStateInterface $form_state, $form_id = NULL) {
    $setting = $this->getSettingElement($form, $form_state);
    $setting->setProperty('disabled', 'disabled');
  }

}
