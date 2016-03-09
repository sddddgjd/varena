<?php

class Attachment extends BaseObject {

  private $user = null;

  function getUser() {
    if (!$this->user) {
      $this->user = User::get_by_id($this->userId);
    }
    return $this->user;
  }
}

?>
