<?php

/**
 * This class handles session-specific variables.
 **/
class Session {
  const ONE_MONTH_IN_SECONDS = 30 * 86400;

  static function init() {
    if (isset($_COOKIE[session_name()])) {
      session_start();
    }
    if (self::getUser() == null) {
      self::loadUserFromCookie();
    }
  }

  static function get($name, $default = null) {
    return (isset($_SESSION[$name]))
      ? $_SESSION[$name]
      : $default;
  }

  static function set($var, $value) {
    // Lazy start of the session so we don't send a PHPSESSID cookie unless we have to
    if (!isset($_SESSION)) {
      session_start();
    }
    $_SESSION[$var] = $value;
  }

  static function unsetVariable($var) {
    if (isset($_SESSION)) {
      unset($_SESSION[$var]);
    }
  }

  static function getUser() {
    return self::get('user');
  }

  private static function kill() {
    if (!isset($_SESSION)) {
      session_start(); // It has to have been started in order to be destroyed.
    }
    session_unset();
    session_destroy();
    if (ini_get("session.use_cookies")) {
      setcookie(session_name(), '', time() - 3600, '/'); // expire it
    }
  }

  static function login($user, $remember) {
    self::set('user', $user);

    if ($remember) {
      $token = base64_encode(random_bytes(33)); // 44 bytes in base64

      $at = Model::factory('AuthToken')->create();
      $at->userId = $user->id;
      $at->selector = base64_encode(random_bytes(9)); // 12 bytes in base64
      $at->token = hash('sha256', $token);
      $at->save();

      $cookieName = Config::get('general.loginCookieName');
      setcookie($cookieName,
                $at->selector . ':' . $token,
                time() + self::ONE_MONTH_IN_SECONDS,
                '/');
      Util::redirect(Util::$wwwRoot);
    }
  }

  static function logout() {
    $cookieName = Config::get('general.loginCookieName');
    if (isset($_COOKIE[$cookieName])) {
      LoginCookie::delete_all_by_value($_COOKIE[$cookieName]);
    }
    setcookie($cookieName, NULL, time() - 3600, '/');
    unset($_COOKIE[$cookieName]);
    self::kill();
    Util::redirect(Util::$wwwRoot);
  }

  static function loadUserFromCookie() {
    $cookieName = Config::get('general.loginCookieName');
    if (!isset($_COOKIE[$cookieName])) {
      return;
    }
    $cookie = LoginCookie::get_by_value($_COOKIE[$cookieName]);
    $user = $cookie ? User::get_by_id($cookie->userId) : null;
    if ($user) {
      self::set('user', $user);
    } else {
      // The cookie is invalid
      setcookie($cookieName, NULL, time() - 3600, '/');
      unset($_COOKIE[$cookieName]);
      if ($cookie) {
        $cookie->delete();
      }
    }
  }

}

?>
