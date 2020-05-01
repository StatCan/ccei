<?php

namespace Drupal\ccei_subject\Plugin\Field\FieldFormatter;

use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'power_bi_formatter' formatter.
 *
 * @FieldFormatter(
 *  id = "power_bi_formatter",
 *  label = @Translation("Power BI iframe Formatter"),
 *  field_types = {
 *    "link"
 *  }
 * )
 */
class PowerBIFormatter extends LinkFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'trim_length' => 80,
      'rel' => '',
      'target' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $entity = $items->getEntity();
    $settings = $this->getSettings();

    foreach ($items as $delta => $item) {
      // By default use the full URL as the link text.
      $url = $this->buildUrl($item);
      $link_title = $url->toString();

      // If the link text field value is available, use it for the text.
      if (empty($settings['url_only']) && !empty($item->title)) {
        // Unsanitized token replacement here because the entire link title
        // gets auto-escaped during link generation in
        // \Drupal\Core\Utility\LinkGenerator::generate().
        $link_title = \Drupal::token()->replace($item->title, [$entity->getEntityTypeId() => $entity], ['clear' => TRUE]);
      }

      // The link_separate formatter has two titles; the link text (as in the
      // field values) and the URL itself. If there is no link text value,
      // $link_title defaults to the URL, so it needs to be unset.
      // The URL version may need to be trimmed as well.
      if (empty($item->title)) {
        $link_title = NULL;
      }
      $url_title = $url->toString();
      if (!empty($settings['trim_length'])) {
        $link_title = Unicode::truncate($link_title, $settings['trim_length'], FALSE, TRUE);
        $url_title = Unicode::truncate($url_title, $settings['trim_length'], FALSE, TRUE);
      }

      $element[$delta] = [
        '#theme' => 'power_bi_formatter',
        '#title' => $link_title,
        '#url_title' => $url_title,
        '#url' => $url,
      ];

      if (!empty($item->_attributes)) {
        // Set our RDFa attributes on the <a> element that is being built.
        $url->setOption('attributes', $item->_attributes);

        // Unset field item attributes since they have been included in the
        // formatter output and should not be rendered in the field template.
        unset($item->_attributes);
      }
    }
    return $element;
  }

}
