<?php

/**
 * Read an email link and allow the user to choose a new password.
 **/

require_once '../../lib/Util.php';

Util::requireNotLoggedIn();

$token = Request::get('token');
$password = Request::get('password');
$password2 = Request::get('password2');

$et = EmailToken::get_by_token($token);

if (!$et) {
  FlashMessage::add(_('You have entered an invalid token.'));
  Http::redirect(Util::$wwwRoot);
}

$u = User::get_by_id($et->userId);
if (!$u) {
  // This would be a bug on our side
  FlashMessage::add(_('Please contact an administrator.'));
  Http::redirect(Util::$wwwRoot);
}

$errors = [];
if ($password || $password2) {
  $u->password = $password;
  $errors = $u->validatePassword($password2);

  if (!count($errors)) {
    $u->password = password_hash($password, PASSWORD_DEFAULT);
    $u->save();
    FlashMessage::add(_('Password changed.'), 'success');
    Session::login($u, false);
  }
}

SmartyWrap::assign('errors', $errors);
SmartyWrap::assign('token', $token);
SmartyWrap::display('auth/newPass.tpl');

?>
