<?php

class StringUtil {
  static function startsWith($string, $substring) {
    $startString = substr($string, 0, strlen($substring));
    return $startString == $substring;
  }

  static function endsWith($string, $substring) {
    $lenString = strlen($string);
    $lenSubstring = strlen($substring);
    $endString = substr($string, $lenString - $lenSubstring, $lenSubstring);
    return $endString == $substring;
  }

  static function charAt($s, $i) {
    return mb_substr($s, $i, 1);
  }

  static function sanitize($s) {
    if (is_string($s)) {
      $s = trim($s);
      $s = str_replace(array("\r", 'ş', 'Ş', 'ţ', 'Ţ'), array('', 'ș', 'Ș', 'ț', 'Ț'), $s);
    }
    return $s;
  }

  static function textile($s,$restricted) {
    require_once Util::$rootPath . '/lib/third-party/Textile/Parser.php';
    require_once Util::$rootPath . '/lib/third-party/Textile/DataBag.php';
    require_once Util::$rootPath . '/lib/third-party/Textile/Tag.php';
    $p = new Netcarver\Textile\Parser();
    return $p->setRestricted($restricted)->parse($s);
  }
}

?>
