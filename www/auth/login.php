<?php 

require_once '../../lib/Util.php';

Util::requireNotLoggedIn();

$method = Request::get('method');
$email = Request::get('email');
$name = Request::get('name');
$password = Request::get('password');
$password2 = Request::get('password2');
$remember = Request::isset('remember');
$errors = [];

if ($method == 'login') {
  $u = User::get_by_email($email);
  if ($u && password_verify($password, $u->password)) {
    Session::login($u, $remember);
  } else {
    $errors['email'] = [ _('Incorrect email or password.') ];
  }
} else if ($method == 'signup') {
  $u = Model::factory('User')->create();
  $u->email = $email;
  $u->name = $name;
  $u->password = $password;
  $errors = $u->validate();
  if ($password != $password2) {
    $errors['password2'][] = _("Passwords don't match.");
  }
  if (!count($errors)) {
    $u->password = password_hash($password, PASSWORD_DEFAULT);
    $u->save();
  }
  Session::login($u, false);
}

SmartyWrap::assign('method', $method);
SmartyWrap::assign('errors', $errors);
SmartyWrap::assign('email', $email);
SmartyWrap::assign('name', $name);
SmartyWrap::assign('remember', $remember);
SmartyWrap::display('auth/login.tpl');

?>
