<?php
/**
 * @file
 * Contains Drupal\content_hub_extend\Source;
 */

namespace Drupal\content_hub_extend;

use Drupal\content_hub_connector as content_hub_connector;
use GuzzleHttp\Exception\ServerException;

class Source {

  const CACHE_KEY = 'content_hub_extend_source_list';

  public static function getAll() {
    $sources = cache_get(self::CACHE_KEY);

    if (!empty($sources)) {
      return $sources->data;
    }

    $client = content_hub_connector_client_load();
    $sources = array();

    try {
      $sources = $client->getSettings()->getClients();
    }
    catch (ServerException $ex) {
      $msg = $ex->getMessage();
      watchdog('content_hub_connector', 'Could not reach the Content Hub [Error message: @msg]', array(
        '@msg' => $msg,
      ), WATCHDOG_ERROR);
    }
    catch (ConnectException $ex) {
      $msg = $ex->getMessage();
      watchdog('content_hub_connector', 'Could not reach the Content Hub. Please verify your hostname URL. [Error message: @msg]', array(
        '@msg' => $msg,
      ), WATCHDOG_ERROR);
    }
    catch (RequestException $ex) {
      $msg = $ex->getMessage();
      watchdog('content_hub_connector', 'Error trying to connect to the Content Hub (Error Message = @error_message)', array(
        '@error_message' => $msg,
      ), WATCHDOG_ERROR);
    }
    catch (Exception $ex) {
      $msg = $ex->getMessage();
      watchdog('content_hub_connector', 'Error trying to connect to the Content Hub (Error Message = @error_message)', array(
        '@error_message' => $msg,
      ), WATCHDOG_ERROR);
    }

    if (empty($sources)) {
      return $sources;
    }

    foreach ($sources as &$source) {
      $source['domain'] = self::getSourceDomain($source['uuid']);
    }

    // We get unfriendly names from CH so this will allow custom modules to map
    // CH names => user friendly names.
    drupal_alter('content_hub_extend_source_list', $sources);
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
    module_load_include('module', 'content_hub');
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