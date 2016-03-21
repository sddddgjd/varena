<?php

class Test extends BaseObject {

  const STATUS_PASSED = 0;
  const STATUS_TLE = 1;
  const STATUS_MLE = 2;
  const STATUS_NONZERO = 3;
  const STATUS_NO_OUTPUT = 4;
  const STATUS_WRONG_ANSWER = 5;
  const STATUS_GRADED = 6;
  private static $STATUS_NAMES = null;

  static function init() {
    self::$STATUS_NAMES = [
      _('passed'),
      _('time limit exceeded'),
      _('memory limit exceeded'),
      _('nonzero exit code'),
      _('no output file'),
      _('wrong answer'),
      _('deferred to grader'),
    ];
  }

  function getStatusName() {
    return self::$STATUS_NAMES[$this->status];
  }
}

Test::init();

?>
