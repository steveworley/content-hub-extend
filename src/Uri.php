<?php
/**
 * @file
 * Contains Drupal\contnet_hub_extend\Uri.
 */

namespace Drupal\content_hub_extend;


class Uri {

  const LOAD_HOOK = '%content_hub_extend';
  const ENTITY_ID = 1;

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
    return str_replace(self::ENTITY_ID, self::LOAD_HOOK . "/ch", $path);
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
      if ($part == self::ENTITY_ID) {
        $key = $index;
        break;
      }
    }

    return array(
      'path' => static::generatePath($this->path),
      'args' => array(
        'page' => array($this->type, $key),
        'access' => array($this->permission, $this->type, $key),
        'load' => array($this->type, $key),
      ),
    );
  }
}