<?php

/**
 * This class loads and queries an INI file. All the setings must go under a section, such as
 * [general]
 * database = "mysql://root@localhost/mydatabase"
 *
 * [someSection]
 * someKey = someValue
 * someOtherKey = someOtherValue
 **/
class Config {
  private static $config = array();

  static function load($fileName) {
    self::$config = parse_ini_file($fileName, true);
  }

  static function get($key, $default = null) {
    list($section, $name) = explode('.', $key, 2);
    return isset(self::$config[$section][$name])
      ? self::$config[$section][$name]
      : $default;
  }
}

?>
