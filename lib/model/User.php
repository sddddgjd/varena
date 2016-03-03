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
   * Validates a user for correctness. If $flashErrors is set, then sets flash error messages.
   */
  function validate($flashErrors = true) {
    $valid = true;

    if (!preg_match("/^[-._ 0-9\p{L}]{3,50}$/u", $this->name)) {
      $valid = false;
      if ($flashErrors) {
        FlashMessage::add(_("Your name must be between 3 and 50 characters long and consist of letters, digits, spaces, '-', '.' and '_'."));
      }
    }

    if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $valid = false;
      if ($flashErrors) {
        FlashMessage::add(_('The email address is invalid.'));
      }
    }

    $otherUser = User::get_by_email($this->email);
    if ($otherUser && ($otherUser->id != $this->id)) {
      $valid = false;
      if ($flashErrors) {
        FlashMessage::add(_('The email address is already in use.'));
      }
    }

    return $valid;
  }

}

?>
