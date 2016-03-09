<?php

/**
 * This class reads request parameters.
 **/
class Request {
  /* Reads a request parameter. */
  static function get($name, $default = null) {
    return array_key_exists($name, $_REQUEST)
      ? StringUtil::sanitize($_REQUEST[$name])
      : $default;
  }

  /* Reads a file record from $_FILES. */
  static function getFiles($name, $default = []) {
    return array_key_exists($name, $_FILES)
      ? $_FILES[$name]
      : $default;
  }

  /* Reads a present-or-not parameter (checkbox, button etc.). */
  static function isset($name) {
    return array_key_exists($name, $_REQUEST);
  }
}

?>
