<?php

/**
 * @file
 * Page render callbacks for content hub extend.
 */

use Drupal\content_hub_connector as content_hub_connector;

/**
 * Page callback for source entity/edit.
 *
 * Redirect users to the source site to edit the content.
 */
function content_hub_extend_source_edit($entity_type, $entity) {
  if (!variable_get('content_hub_connector_origin')) {
    drupal_set_message(t('Content hub is not connected.', 'error'));
    drupal_not_found();
  }

  // To get the source URL for a piece of content we need to make a search
  // request to content hub.
  module_load_include('inc', 'content_hub', 'content_hub.search');

  $results = content_hub_build_search_query($entity->uuid);

  if (empty($results['hits'])) {
    drupal_set_message(t('Unable to locate source entity'), 'warning');
    drupal_goto(entity_url($entity_type, $entity));
  }

  $results = content_hub_format_content($results['hits']);
  $result = reset($results);

  $redirect_url = t('!entity_url/edit', array('!entity_url' => $result['url']));
  drupal_alter('content_hub_extend_source_redirect', $redirect_url);

  drupal_goto($redirect_url, array('external' => TRUE));
}