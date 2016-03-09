<?php

class Attachment extends BaseObject {

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

  function getFullPath() {
    return sprintf("%s/uploads/%s/%s",
                   Util::$rootPath,
                   $this->getProblem()->name,
                   $this->name);
  }
}

?>
