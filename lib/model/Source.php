<?php

class Source extends BaseObject {

  const STATUS_PENDING = 0;

  static $ACCEPTED_EXTENSIONS = ['c', 'cpp'];

  private $user = null;
  private $problem = null;

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

?>
