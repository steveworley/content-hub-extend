<?php
/**
 * @file
 * Contains Drupal\content_hub_extend\Source;
 */

namespace Drupal\content_hub_extend;

use Drupal\content_hub_connector as content_hub_connector;

class Source {

  const CACHE_KEY = 'content_hub_extend_source_list';

  public static function getAll() {
    $sources = cache_get(SELF::CACHE_KEY);

    if (!empty($sources)) {
      return $sources->data;
    }

    $sources = content_hub_get_sources();

    foreach ($sources as &$source) {
      $source['domain'] = self::getSourceDomain($source['uuid']);
    }

    cache_set(SELF::CACHE_KEY, $sources);
    return $sources;
  }

  /**
   * Translate a source ID to a domain.
   *
   * @param string $source
   *   Source UUID.
   */
  public static function getSourceDomain($source) {
    module_load_include('inc', 'content_hub', 'content_hub.search');
    $results = content_hub_build_search_query($source);

    if (empty($results['hits'])) {
      return FALSE;
    }

    $results = content_hub_format_content($results['hits']);
    $result = reset($results);

    $url = parse_url($result['url']);
    return strtr('scheme://host', $url);
  }
}