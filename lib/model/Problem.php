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
   * Returns an array of [first, last] pairs. Throws an exception if
   * testGroups is inconsistent.
   **/
  function getTestGroups() {
    $result = [];

    if (!$this->testGroups) {
      for ($i = 1; $i <= $this->numTests; $i++) {
        $result[] = [$i, $i];
      }
    } else {
      $prev = 0;
      $groups = explode(';', $this->testGroups);

      foreach ($groups as $i => $g) {
        $parts = explode('-', $g);
        if (count($parts) == 1) {
          $first = $last = $parts[0]; // single test case
        } else if (count($parts) == 2) {
          list($first, $last) = $parts;
        } else {
          throw new Exception(sprintf(_('Too many dashes in group %d.'), $i + 1));
        }

        if (!ctype_digit($first) || !ctype_digit($last)) {
          throw new Exception(sprintf(_('Illegal character in group %d.'), $i + 1));
        }

        if ($first > $last) {
          throw new Exception(sprintf(_('Wrong order in group %d.'), $i + 1));
        }

        if ($first != $prev + 1) {
          throw new Exception(sprintf(_('Group %d should start at test %d.'),
                                      $i + 1, $prev + 1));
        }

        if ($last > $this->numTests) {
          throw new Exception(sprintf(_('Value exceeds number of tests in group %d.'), $i + 1));
        }

        $result[] = [$first, $last];
        $prev = $last;
      }

      if ($prev != $this->numTests) {
        throw new Exception(sprintf(_('Tests %d through %d are missing.'),
                                    $prev + 1, $this->numTests));
      }
    }

    return $result;
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

    try {
      $this->getTestGroups();
    } catch (Exception $e) {
      $errors['testGroups'] = $e->getMessage();
    }

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

  // TODO: either disable renaming or, when renaming, rename the attachment directory.
}

?>
