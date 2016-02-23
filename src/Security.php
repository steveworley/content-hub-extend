<?php
/**
 * @file
 * Contains Drupal\content_hub_extend\Security.
 */

namespace Drupal\content_hub_extend;

use Drupal\content_hub_connector as content_hub_connector;

class Security {

  /**
   * Get an entities origin.
   *
   * @param $entity_type
   *   The entity type.
   * @param $entity_id
   *   The entity id.
   *
   * @return null|string
   *   The entities origin.
   */
  public function getEntityOrigin($entity_type, $entity_id) {
    // Make sure we have access to the required classes.
    content_hub_connector_init();

    $entity = content_hub_connector\ContentHubImportedEntities::loadByDrupalEntity($entity_type, $entity_id);
    return !empty($entity) ? $entity->getOrigin() : NULL;
  }

  /**
   * Access check for a given entity.
   *
   * @param $entity_type
   * @param $entity
   * @param $origin
   * @return bool
   * @throws \EntityMalformedException
   */
  public function accessCheck($entity_type, $entity, $origin = NULL) {
    if (!$origin) {
      return FALSE;
    }

    list($entity_id,,) = entity_extract_ids($entity_type, $entity);
    $entity_origin = $this->getEntityOrigin($entity_type, $entity_id);

    return $origin !== $entity_origin;
  }

}