<?php
/**
 * @file
 * Defines API functions exposed via content_hub_extend.
 */

/**
 * Alter the CH source list.
 *
 * This allows you to change the name or add arbitrary domains to the contenthub
 * domain selector tool. The source list will only return domains from your
 * content network that have indexed content into content hub.
 *
 * @param array &$sources
 *   A list of domains who have indexed content to content hub.
 */
function HOOK_content_hub_extend_source_list_alter(&$sources) {
  // Add an option to the list of available content hub sources.
  $sources['http://newdomain.com'] = 'name';

  // Provide a customer friendly name for a particular domain.
  $sources['http://mydomain.com'] = 'new name';
}