<?php
/**
 * @file
 * Contains Drupal\contnet_hub_extend\Uri.
 */

namespace Drupal\content_hub_extend;


class Uri {

  const LOAD_HOOK = '%content_hub_extend';
  const ENTITY_ID = 1;
  const ENTITY_NAME = 'temp_entity';

  private $data = array();

  /**
   * Uri constructor.
   *
   * @param $path
   * @param $type
   * @param string $permission
   */
  public function __construct($path, $type, $permission = 'access content') {
    $this->path = $path;
    $this->parts = explode('/', $path);
    $this->type = $type;
    $this->permission = $permission;

    return $this;
  }

  public static function generatePath($path) {
    return str_replace(self::searchers(), self::LOAD_HOOK . "/ch", $path);
  }

  /**
   * Words that can be present in the entity uri.
   *
   * @return array
   */
  public static function searchers() {
    return array(self::ENTITY_ID, self::ENTITY_NAME);
  }

  /**
   * Dot notation accessor.
   *
   * @param null $path
   * @param null $default
   *
   * @return mixed
   *   A point in $this->data.
   */
  public function get($path = NULL, $default = NULL) {
    $array = $this->data;

    if (empty($path)) {
      return $default;
    }

    $keys = explode('.', $path);
    foreach ($keys as $key) {
      if (isset($array[$key])) {
        $array = $array[$key];
      }
    }

    return $array == $this->data ? $default : $array;
  }

  /**
   * Resolve a URI definition.
   *
   * Take the given URL and build a content hub path with valid arguments.
   *
   * @return array
   */
  public function resolve() {
    $key = 0;

    foreach ($this->parts as $index => $part) {
      if (in_array($part, self::searchers())) {
        $key = $index;
        break;
      }
    }

    $this->data = array(
      'path' => static::generatePath($this->path),
      'args' => array(
        'page' => array($this->type, $key),
        'access' => array($this->permission, $this->type, $key),
        'load' => array($this->type, $key),
      ),
    );

    return $this;
  }
}