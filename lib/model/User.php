<?php

class User extends BaseObject {

  function getDisplayName() {
    $s = $this->email;
    if ($this->name) {
      $s = $this->name;
    }
    return StringUtil::shortenString($s, 30);
  }

  /**
   * Validates a user for correctness. Returns an array of errors.
   **/
  function validate() {
    $errors = [];

    if (!preg_match("/^[-._ 0-9\p{L}]{3,50}$/u", $this->name)) {
      $errors[] = _("Your name must be between 3 and 50 characters long and consist of letters, digits, spaces, '-', '.' and '_'.");
    }

    if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = _('The email address is invalid.');
    }

    $otherUser = Model::factory('User')
               ->where('email', $this->email)
               ->where_not_equal('id', $this->id)
               ->find_one();
    if ($otherUser) {
      $errors[] = _('The email address is already in use.');
    }

    $l = strlen($this->password);
    if ($l < 6 || $l > 200) {
      $errors[] = _('The password must be between 6 and 200 characters long.');
    }

    return $errors;
  }

}

?>
