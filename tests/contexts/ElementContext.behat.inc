<?php

namespace Drupal\CCEIExtension\Context;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use Drupal\DrupalExtension\Context\DrupalSubContextBase;

/**
 * Contains miscellaneous step definitions for working with HTML elements.
 *
 * @internal
 *   This is an internal part of CCEI's testing system and may be
 *   changed or removed at any time without warning. It should not be extended,
 *   instantiated, or used in any way by external code! If you need to use this
 *   functionality, you should copy the relevant code into your own project.
 */
final class ElementContext extends DrupalSubContextBase {

  /**
   * Clicks an arbitrary element, found by CSS selector.
   *
   * @param string $selector
   *   The CSS selector.
   *
   * @When I scroll down and click the :selector element
   */
  public function click($selector) {
    $element = $this->assertSession()->elementExists('css', $selector);

    try {
      $this->scrollDownTo($selector);
    }
    catch (UnsupportedDriverActionException $e) {
      // Don't worry about it.
    }

    $element->click();
  }

  /**
   * Scrolls an element into the viewport.
   *
   * @param string $selector
   *   The element's CSS selector.
   *
   * @When I scroll down to the :selector element
   */
  public function scrollDownTo($selector) {
    // Pass false to scrollIntoView so it scrolls with the element at the bottom
    // of the page. Otherwise the admin bar overlaps everything we try and click
    // (on node edit as an admin).
    $this->getSession()
      ->executeScript('document.querySelector("' . addslashes($selector) . '").scrollIntoView(false)');
  }

}
