<?php

Util::init();

/**
 * This class is the only one that needs to be called explicitly from every web page or script.
 * It loads everything else on demand.
 **/
class Util {
  static $rootPath;
  static $wwwRoot;
  
  static function init() {
    self::definePaths();
    spl_autoload_register('self::autoloadClasses');
    Config::load(self::$rootPath . "/varena2.conf");
    self::setLocale();
    $tp = self::$rootPath . '/lib/third-party'; // third-party libs
    require_once "{$tp}/idiorm/idiorm.php";
    require_once "{$tp}/idiorm/paris.php";
    require_once "{$tp}/smarty/Smarty.class.php";
    Db::init(Config::get('general.database'));
    Session::init();
    FlashMessage::restoreFromSession();
    SmartyWrap::init();
  }

  private static function definePaths() {
    self::$rootPath = realpath(__DIR__ . '/..');
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $pos = strrpos($scriptName, '/www/');
    self::$wwwRoot = ($pos === false) ? '/' : substr($scriptName, 0, $pos + 5);
  }

  static function autoloadClasses($className) {
    $paths = ['/lib', '/lib/model', '/lib/third-party'];
    foreach ($paths as $p) {
      $fileName = self::$rootPath . "{$p}/{$className}.php";
      if (file_exists($fileName)) {
        require_once($fileName);
        return;
      }
    }
  }

  private static function setLocale() {
    mb_internal_encoding("UTF-8");
    setlocale(LC_ALL, Config::get('general.locale'));
    $domain = "messages";
    bindtextdomain($domain, self::$rootPath . '/locale');
    bind_textdomain_codeset($domain, 'UTF-8');
    textdomain($domain);
  }

  static function getFullServerUrl() {
    $host = $_SERVER['SERVER_NAME'];
    $port =  $_SERVER['SERVER_PORT'];
    $path = self::$wwwRoot;

    return ($port == '80') ? "http://$host$path" : "http://$host:$port$path";
  }

  static function requireNotLoggedIn() {
    if (Session::getUser()) {
      FlashMessage::add(_('You are already logged in.'), 'warning');
      Http::redirect(self::$wwwRoot);
    }
  }

  static function requireLoggedIn() {
    if (!Session::getUser()) {
      FlashMessage::add(_('Please log in to continue.'), 'warning');
      Http::redirect(self::$wwwRoot . 'auth/login');
    }
  }

  static function requireAdmin() {
    self::requireLoggedIn();
    if (!Session::getUser()->admin) {
      FlashMessage::add(_('Access denied.'));
      Http::redirect(self::$wwwRoot);
    }
  }

  /* Notify the appropriate evaluator that a new source is available. */
  static function notifyEvaluator($source) {
    $port = Config::get('eval.port');
    $ips = Config::get('eval.ip');
    $index = $source->id % count($ips);

    $sock = socket_create(AF_INET, SOCK_STREAM, 0)
            or die("Socket create error.\n");
    socket_set_nonblock($sock);

    // Try to open a connection, but don't make a fuss if we can't.
    // The evaluator may be down at the moment.
    @socket_connect($sock, $ips[$index], $port);
    socket_close($sock);
  }
}

?>
