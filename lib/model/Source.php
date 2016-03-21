<?php

class Source extends BaseObject {

  const STATUS_NEW = 0;
  const STATUS_PENDING = 1;
  const STATUS_NO_TESTS = 2;
  const STATUS_NO_GRADER = 3;
  const STATUS_GRADER_ERROR = 4;
  const STATUS_NO_SOURCE = 5;
  const STATUS_COMPILE_ERROR = 6;
  const STATUS_DONE = 7;
  private static $STATUS_NAMES = null;

  static $ACCEPTED_EXTENSIONS = ['c', 'cpp'];

  private $user = null;
  private $problem = null;

  static function init() {
    self::$STATUS_NAMES = [
      _('new'),
      _('pending'),
      _('missing test files'),
      _('missing grader'),
      _('grader does not compile'),
      _('missing source file'), // this would be weird
      _('compilation error'),
      _('done'),
    ];
  }

  function getStatusName() {
    return self::$STATUS_NAMES[$this->status];
  }

  function getUser() {
    if (!$this->user) {
      $this->user = User::get_by_id($this->userId);
    }
    return $this->user;
  }

  function getProblem() {
    if (!$this->problem) {
      $this->problem = Problem::get_by_id($this->problemId);
    }
    return $this->problem;
  }
}

Source::init();

?>
