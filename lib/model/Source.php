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
  static $STATUSES_WITH_SCORE = [self::STATUS_DONE, self::STATUS_COMPILE_ERROR];

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

  /**
   * Computes this->score based on Test->score and Problem->testGroups.
   * Returns intermediary data: points per test, points per test group.
   * Does not call save().
   */
  function computeScore() {
    if (!$this->hasTests()) {
      $this->score = 0;
      return null;
    }

    // Load the tests and map them by number
    $tests = Model::factory('Test')
           ->where('sourceId', $this->id)
           ->order_by_asc('number')
           ->find_many();
    $tmap = [];
    foreach ($tests as $t) {
      $tmap[$t->number] = $t;
    }

    $points = $this->getProblem()->getTestPoints();
    $groups = $this->getProblem()->getTestGroups();
    $this->score = 0;

    foreach ($groups as &$g) {
      $first = $g['first'];
      $last = $g['last'];

      if ($first == $last) {
        // single tests always count
        $t = $tmap[$first];
        $g['score'] = $points[$t->number] * $t->score / 100;
      } else {
        // grouped tests get all or nothing
        $passed = true;
        $score = 0;
        for ($i = $first; $i <= $last; $i++) {
          $t = $tmap[$i];
          $passed &= ($t->score == 100);
          $score += $points[$t->number];
        }
        if ($passed) {
          $g['score'] = $score;
        } else {
          $g['score'] = 0;
        }
      }

      $this->score += $g['score'];
    }

    return [
      'groups' => $groups,
      'points' => $points,
    ];
  }

  /* Returns true if the status indicates the score field is meaningful. */
  function hasScore() {
    return in_array($this->status, self::$STATUSES_WITH_SCORE);
  }

  /* Returns true if the status indicates the existence of test results. */
  function hasTests() {
    return $this->status == self::STATUS_DONE;
  }
}

Source::init();

?>
