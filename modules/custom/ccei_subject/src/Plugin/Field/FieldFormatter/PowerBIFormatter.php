<?php

namespace Drupal\ccei_subject\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\iframe\Plugin\Field\FieldFormatter\IframeDefaultFormatter;

/**
 * Plugin implementation of the 'power_bi_formatter' formatter.
 *
 * @FieldFormatter(
 *  id = "power_bi_formatter",
 *  label = @Translation("Power BI iframe Formatter"),
 *  field_types = {
 *    "iframe"
 *  }
 * )
 */
class PowerBIFormatter extends IframeDefaultFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $field_settings = $this->getFieldSettings();
    $settings = $this->getSettings();
    $entity = $items->getEntity();
    foreach ($items as $delta => $item) {
      if (empty($item->url)) {
        continue;
      }
      if (!isset($item->title)) {
        $item->title = '';
      }
      $elements[$delta] = self::iframeIframe($item->title, $item->url, $item);
      $elements[$delta]['#theme'] = 'power_bi_formatter';
      // Tokens can be dynamic, so its not cacheable.
      if (isset($settings['tokensupport']) && $settings['tokensupport']) {
        $elements[$delta]['cache'] = ['max-age' => 0];
      }
    }
    return $elements;
  }

}
