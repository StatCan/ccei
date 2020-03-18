<?php

namespace Drupal\Tests\ccei\Kernel;

use Drupal\Core\Extension\Extension;
use Drupal\KernelTests\KernelTestBase;
use Drupal\ccei\ComponentDiscovery;

/**
 * @group ccei
 *
 * @coversDefaultClass \Drupal\ccei\ComponentDiscovery
 */
class ComponentDiscoveryTest extends KernelTestBase {

  /**
   * The ComponentDiscovery under test.
   *
   * @var \Drupal\ccei\ComponentDiscovery
   */
  protected $discovery;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->discovery = new ComponentDiscovery(
      $this->container->get('app.root')
    );
  }

  /**
   * @covers ::getAll
   */
  public function testGetAll() {
    $components = $this->discovery->getAll();

    $this->assertInstanceOf(Extension::class, $components['ccei_core']);
  }

  /**
   * @covers ::getMainComponents
   */
  public function testGetMainComponents() {
    $components = $this->discovery->getMainComponents();

    $this->assertInstanceOf(Extension::class, $components['ccei_core']);
  }

  /**
   * @covers ::getSubComponents
   */
  public function testGetSubComponents() {
    $components = $this->discovery->getSubComponents();

    $this->assertArrayNotHasKey('ccei_core', $components);
  }

}
