<?php

class FlashMessage {
  public static $messages = [];
  public static $type = '';
  private static $anyErrors = false;

  static function add($message, $type = 'danger') {
    self::$messages[] = $message;
    self::$type = $type;
    self::$anyErrors |= ($type == 'danger');
  }

  static function getMessages() {
    return self::$messages;
  }

  static function hasErrors() {
    return self::$anyErrors;
  }

  static function getMessageType() {
    return self::$type ? self::$type : null;
  }

  static function saveToSession() {
    if (count(self::$messages)) {
      Session::set('flashMessages', self::$messages);
      Session::set('flashMessageType', self::$type);
    }
  }

  static function restoreFromSession() {
    if (($messages = Session::get('flashMessages')) &&
        ($type = Session::get('flashMessageType'))) {
      self::$messages = $messages;
      self::$type = $type;
      Session::unsetVariable('flashMessages');
      Session::unsetVariable('flashMessageType');
    }
  }
}

?>
