<?php

namespace Drupal\Tests\ccei\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests CCEI installation profile expectations.
 *
 * @group ccei
 */
class CCEITest extends BrowserTestBase {

  /**
   * Installation profile.
   *
   * @var string
   */
  protected $profile = 'ccei';

  /**
   * Test for the login.
   */
  public function testOpenDataLogin() {
    // Create a user to check the login.
    $user = $this->createUser();

    // Log in our user.
    $this->drupalLogin($user);

    // Verify that logged in user can access the logout link.
    $this->drupalGet('user');

    $this->assertLinkByHref('/user/logout');
  }

}
