<?php

class Problem extends BaseObject {
  const MIN_TESTS = 1;
  const MAX_TESTS = 100;

  private $html = null;

  function getHtml() {
    if ($this->html === null) {
      $p = new ParsedownExtra();
      $this->html = $p->text($this->statement);
    }
    return $this->html;
  }
  
  /**
   * Validates a problem for correctness. Returns an array of { field => array of errors }.
   **/
  function validate() {
    $errors = [];

    if (mb_strlen($this->name) < 2) {
      $errors['name'] = _('The problem name must be at least 2 characters long.');
    }

    $other = Model::factory('Problem')
           ->where('name', $this->name)
           ->where_not_equal('id', (int) $this->id) // could be "" when adding a new problem
           ->find_one();
    if ($other) {
      $errors['name'] = _('There already exists a problem with this name.');
    }

    if (!$this->statement) {
      $errors['statement'] = _('The statement cannot be empty.');
    }

    if ($this->numTests < self::MIN_TESTS ||
        $this->numTests > self::MAX_TESTS) {
      $errors['numTests'] = sprintf(_('Problems must have between %d and %d tests.'),
                                    self::MIN_TESTS,
                                    self::MAX_TESTS);
    }

    if ($this->timeLimit <= 0) {
      $errors['timeLimit'] = _('The time limit must be positive.');
    }

    if ($this->memoryLimit <= 0) {
      $errors['memoryLimit'] = _('The memory limit must be positive.');
    }

    // TODO validate testGroups

    return $errors;
  }

  /**
   * Current policy:
   * * anonymous users can not edit anything (duh);
   * * admins can edit all problems;
   * * users can edit problems they created.
   **/
  function editableBy($user) {
    return $user &&
      ($user->admin ||
       ($user->id == $this->userId));
  }

  /**
   * Current policy: true
   **/
  function testsViewableBy($user) {
    return true;
  }

}

?>
