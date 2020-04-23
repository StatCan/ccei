<?php

namespace Drupal\ccei_subject\Plugin\Field\FieldFormatter;

use Drupal\Core\Template\Attribute;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Component\Render\HtmlEscapedText;
use Drupal\iframe\Plugin\Field\FieldFormatter\IframeDefaultFormatter;
use Drupal\Core\Url;

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
      // Since I can't get the iframe field foramtter settings to work in the
      // UI. I will override it here for our specific need.
      $elements[$delta] = self::renderIframe($item->title, $item->url, $item);
      // Use our own theme template.
      $elements[$delta]['#theme'] = 'power_bi_formatter';
      // Tokens can be dynamic, so its not cacheable.
      if (isset($settings['tokensupport']) && $settings['tokensupport']) {
        $elements[$delta]['cache'] = ['max-age' => 0];
      }
    }
    return $elements;
  }

  /**
   * This is a hack because I couldn't get the IframeDefaultFormatter settings
   * to work properly.
   */
  public static function renderIframe($text, $path, $item) {
    $options = [];
    $allow = [];
    $style = '';

    // Remove all HTML and PHP tags from a tooltip.
    // For best performance, we act only
    // if a quick strpos() pre-check gave a suspicion
    // (because strip_tags() is expensive).
    if  (!empty($item->title)) {
      $options['title'] = $item->title;
      if (strpos($options['title'], '<') !== FALSE) {
        $options['title'] = strip_tags($options['title']);
      }
    }

    if (\Drupal::moduleHandler()->moduleExists('token')) {
      // Token Support for field "url" and "title".
      $tokensupport = $item->getTokenSupport();
      $tokencontext = ['user' => \Drupal::currentUser()];
      if (isset($GLOBALS['node'])) {
        $tokencontext['node'] = $GLOBALS['node'];
      }
      if ($tokensupport > 0) {
        $text = \Drupal::token()->replace($text, $tokencontext);
      }
      if ($tokensupport > 1) {
        $path = \Drupal::token()->replace($path, $tokencontext);
      }
    }

    $options_link = [];
    $options_link['attributes'] = [];
    $options_link['attributes']['title'] = $options['title'];
    $srcuri = Url::fromUri($path, $options_link);
    $src = $srcuri->toString();
    $options['src'] = $src;
    $drupal_attributes = new Attribute($options);

    $render_array = [
      '#theme' => 'iframe',
      '#src' => $src,
      '#attributes' => $drupal_attributes,
      '#text' => (isset($options['html']) && $options['html'] ? $text : new HtmlEscapedText($text)),
      '#style' => 'iframe#' . $htmlid . ' {' . $style . '}',
    ];
    return $render_array;
  }

}
