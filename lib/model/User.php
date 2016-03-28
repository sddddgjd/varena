<?php

class User extends BaseObject {

  /**
   * Validates a user for correctness. Returns an array of { field => array of errors }.
   **/
  function validate() {
    $id = $this->id ? $this->id : 0; // users have no ID during signup

    $errors = [];

    if (!preg_match("/^[-._0-9\p{L}]{3,25}$/u", $this->username)) {
      $errors['username'][] = _("Your username must be between 3 and 25 characters long and consist of letters, digits, '-', '.' and '_'.");
    } else if (preg_match_all("/\p{L}/u", $this->username) < 3) {
      $errors['username'][] = _('Your username must contain at least three letters.');
    }

    $otherUser = Model::factory('User')
               ->where('username', $this->username)
               ->where_not_equal('id', $id)
               ->find_one();
    if ($otherUser) {
      $errors['username'][] = _('This username is taken.');
    }

    if ($this->name && !preg_match("/^[-. \p{L}]{3,50}$/u", $this->name)) {
      $errors['name'][] = _("Your name must be between 3 and 50 characters long and consist of letters, spaces, '-' and '.'.");
    }

    if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'][] = _('The email address is invalid.');
    }

    $otherUser = Model::factory('User')
               ->where('email', $this->email)
               ->where_not_equal('id', $id)
               ->find_one();
    if ($otherUser) {
      $errors['email'][] = _('The email address is already in use.');
    }

    $l = strlen($this->password);
    if ($l < 6 || $l > 200) {
      $errors['password'][] = _('The password must be between 6 and 200 characters long.');
    }

    return $errors;
  }

}

?>
