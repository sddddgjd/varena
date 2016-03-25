<?php

class Test extends BaseObject {

  const STATUS_PASSED = 0;
  const STATUS_TLE = 1;
  const STATUS_MLE = 2;
  const STATUS_NONZERO = 3;
  const STATUS_JAILED = 4;
  const STATUS_NO_OUTPUT = 5;
  const STATUS_WRONG_ANSWER = 6;
  const STATUS_GRADED = 7;

  function getMessage() {
    switch ($this->status) {
      case self::STATUS_PASSED:
        return _('passed');

      case self::STATUS_TLE:
        return _('time limit exceeded');

      case self::STATUS_MLE:
        return _('memory limit exceeded');

      case self::STATUS_NONZERO:
        return sprintf(_('nonzero exit code (%d)'), $this->exitCode);

      case self::STATUS_JAILED:
        return sprintf(_('terminated by jail (%s)'), $this->message);

      case self::STATUS_NO_OUTPUT:
        return _('no output file');

      case self::STATUS_WRONG_ANSWER:
        return _('wrong answer');

      case self::STATUS_GRADED:
        return sprintf(_('graded (%s)'), $this->message);
    }
  }
}

?>
