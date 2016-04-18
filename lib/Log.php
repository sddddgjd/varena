<?php

Log::init();

class Log {
  static $file;
  static $level;

  static function init() {
    $fileName = Config::get('logging.file');
    self::$file = fopen($fileName, 'a');
    self::$level = Config::get('logging.level'); // no constant() call needed
    @chmod($fileName, 0666);
  }

  // TODO print the file and line number
  static function write($level, $format, $args) {
    if ($level <= self::$level) {
      $date = date("Y-m-d H:i:s");
      vfprintf(self::$file, "[{$date}] {$format}\n", $args);
    }
  }

  static function emergency($format, ...$args) {
    self::write(LOG_EMERG, $format, $args);
  }

  static function alert($format, ...$args) {
    self::write(LOG_ALERT, $format, $args);
  }

  static function critical($format, ...$args) {
    self::write(LOG_CRIT, $format, $args);
  }

  static function error($format, ...$args) {
    self::write(LOG_ERR, $format, $args);
  }

  static function warning($format, ...$args) {
    self::write(LOG_WARNING, $format, $args);
  }

  static function notice($format, ...$args) {
    self::write(LOG_NOTICE, $format, $args);
  }

  static function info($format, ...$args) {
    self::write(LOG_INFO, $format, $args);
  }

  static function debug($format, ...$args) {
    self::write(LOG_DEBUG, $format, $args);
  }



}
