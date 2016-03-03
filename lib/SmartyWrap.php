<?php

class SmartyWrap {
  private static $theSmarty = null;
  private static $cssFiles = array();
  private static $jsFiles = array();

  static function init() {
    self::$theSmarty = new Smarty();
    self::$theSmarty->template_dir = Util::$rootPath . '/templates';
    self::$theSmarty->compile_dir = Util::$rootPath . '/templates_c';
    self::assign('wwwRoot', Util::$wwwRoot);
    self::assign('user', Session::getUser());
    self::addCss('bootstrap', 'main');
    self::addJs('jquery', 'bootstrap');
  }

  static function assign($name, $value) {
    self::$theSmarty->assign($name, $value);
  }

  static function fetchEmail($templateName) {
    $result = self::$theSmarty->fetch('email/' . $templateName);
    return str_replace("\n", "\r\n", $result); // Acording to specs
  }

  static function display($templateName) {
    // TODO convert to inherited templates
    self::assign('cssFiles', self::$cssFiles);
    self::assign('jsFiles', self::$jsFiles);
    self::assign('templateName', $templateName);
    self::assign('flashMessage', FlashMessage::getMessage());
    self::assign('flashMessageType', FlashMessage::getMessageType());
    self::$theSmarty->display('layout.tpl');
  }

  static function addCss(/* Variable-length argument list */) {
    // Note the priorities. This allows files to be added in any order, regardless of dependencies
    foreach (func_get_args() as $id) {
      switch ($id) {
        case 'bootstrap':
          self::$cssFiles[1] = 'bootstrap-3.3.6.min.css';
          self::$cssFiles[2] = 'bootstrap-theme-3.3.6.min.css';
          break;
        case 'main':               self::$cssFiles[3] = 'main.css'; break;
        default:
          FlashMessage::add("Cannot load CSS file {$id}");
          Util::redirect(Util::$wwwRoot);
      }
    }
  }

  static function addJs(/* Variable-length argument list */) {
    // Note the priorities. This allows files to be added in any order, regardless of dependencies
    foreach (func_get_args() as $id) {
      switch($id) {
        case 'jquery':           self::$jsFiles[1] = 'jquery-2.2.1.min.js'; break; 
        case 'bootstrap':        self::$jsFiles[2] = 'bootstrap-3.3.6.min.js'; break; 
        default:
          FlashMessage::add("Cannot load JS script {$id}");
          Util::redirect(Util::$wwRoot);
      }
    }
  }
}

?>
