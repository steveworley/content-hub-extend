<?php
/**
 * @file
 * Contains Drupal\contnet_hub_extend\Uri.
 */

namespace Drupal\content_hub_extend;


class Uri {

  public function __construct($path, $type, $permission = 'access content') {
    $path = strpos($path, 'edit') > -1 ? $path : $path . '/edit';

    $this->path = $path;
    $this->parts = explode('/', $path);
    $this->type = $type;
    $this->permission = $permission;

    return $this;
  }

  public function resolve() {
    $key = 1;

    foreach ($this->parts as $index => $part) {
      if ($part == 1) {
        $key = $index;
      }
    }

    return array(
      'path' => str_replace(1, "%content_hub_extend/ch/{$this->type}", $this->path),
      'args' => array(
        'page' => array($this->type, $key),
        'access' => array($this->permission, $this->type, $key),
      ),
    );

  }
}