<?php

/**
 * @file
 * Contains Drupal\content_hub_extend\Test\ContentHubExtendUriTest.
 */

namespace Drupal\content_hub_extend\Test;

use Drupal\content_hub_extend\Uri;

class ContentHubExtendUriTest extends \PHPUnit_Framework_TestCase {

  /**
   * Ensure the load hook is callable.
   */
  public function testLoader() {
    include dirname(__FILE__) . '/../content_hub_extend.module';
    $load_hook = str_replace('%', '', Uri::LOAD_HOOK) . '_load';
    $this->assertTrue(function_exists($load_hook));
  }

  /**
   * Ensure that resolve returns expected values.
   */
  public function testResolve() {
    $uri = new Uri('path/to/entity/1', 'entity');
    $result = $uri->resolve();

    $this->assertArrayHasKey('path', $result);
    $this->assertArrayHasKey('args', $result);
    $this->assertArrayHasKey('page', $result['args']);
    $this->assertArrayHasKey('access', $result['args']);
    $this->assertArrayHasKey('load', $result['args']);
  }

  /**
   * Ensure a custom permission can be resolved.
   */
  public function testResolvePermission() {
    $uri = new Uri('path/to/entity/1', 'entity', 'custom entity');
    $result = $uri->resolve();
    $this->assertContains('custom entity', $result['args']['access']);
  }

  /**
   * Ensure that a valid path can be generated.
   */
  public function testGeneratePath() {
    $path = Uri::generatePath('path/to/entity/' . Uri::ENTITY_ID);
    $this->assertContains(Uri::LOAD_HOOK, $path);
    $this->assertNotContains((string) Uri::ENTITY_ID, $path);
  }
}