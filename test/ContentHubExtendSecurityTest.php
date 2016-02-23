<?php

/**
 * Mock entity_extract_ids().
 */
namespace {
  function entity_extract_ids($entity_type, $entity) {
    return array(1, 1, 1);
  }
}

namespace Drupal\content_hub_extend\Test {

  class ContentHubExtendSecurityTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test expected output if the entity is being access on the same origin.
     */
    public function testSameOrigin() {
      $origin_uuid = '2b4fb0ce-d81b-42e6-4dc3-ebcda3bb09ac';

      $security = $this->getMockBuilder('\Drupal\content_hub_extend\Security')
        ->setMethods(array('getEntityOrigin'))
        ->getMock();

      $security->expects($this->any())
        ->method('getEntityOrigin')
        ->willReturn($origin_uuid);

      $access = $security->accessCheck('node', (object) array('nid' => 1), $origin_uuid);
      $this->assertFalse($access);
    }

  /**
   * Test expected output if the entity is being access on a different origin.
   */
      public function testRemoteSource() {
        $origin_uuid = '2b4fb0ce-d81b-42e6-4dc3-ebcda3bb09ac';
        $source_uuid = '3b4fb0ce-d814-02e6-4dc3-ebcda3bb09ac';

        $security = $this->getMockBuilder('\Drupal\content_hub_extend\Security')
          ->setMethods(array('getEntityOrigin'))
          ->getMock();

        $security->expects($this->any())
          ->method('getEntityOrigin')
          ->willReturn($source_uuid);

        $access = $security->accessCheck('node', (object) array('nid' => 1), $origin_uuid);
        $this->assertTrue($access);
      }

  }
}