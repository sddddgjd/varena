<?php

class Problem extends BaseObject {
  
  /**
   * Validates a problem for correctness. Returns an array of errors.
   **/
  function validate() {
    $errors = [];

    if (mb_strlen($this->name) < 2) {
      $errors[] = _('The problem name must be at least 2 characters long.');
    }

    if (!$this->statement) {
      $errors[] = _('The statement cannot be empty.');
    }

    $other = Model::factory('Problem')
           ->where('name', $this->name)
           ->where_not_equal('id', $this->id)
           ->find_one();
    if ($other) {
      $errors[] = _('There exists already a problem with this name.');
    }

    return $errors;
  }

}

?>
