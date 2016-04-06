<?php

class Attachment extends BaseObject {
  // Special permissions are required to upload files matching this prefix.
  const PREFIX_GRADER = 'grader_';

  const PATTERN_TEST_IN = 'grader_test%d.in';
  const PATTERN_TEST_OK = 'grader_test%d.ok';
  const PATTERN_GRADER = 'grader_%s';

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
    return sprintf("%s/uploads/attachments/%s/%s",
                   Util::$rootPath,
                   $this->getProblem()->name,
                   $this->name);
  }

  function delete() {
    @unlink($this->getFullPath());
    return parent::delete();
  }
}

?>
