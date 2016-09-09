<?php 

require_once '../../lib/Util.php';

Util::requireNotLoggedIn();

$method = Request::get('method');
$email = Request::get('email');
$username = Request::get('username');
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
    FlashMessage::add(_('Incorrect email or password'));
  }
} else if ($method == 'signup') {
  $u = Model::factory('User')->create();
  $u->email = $email;
  $u->username = $username;
  $u->name = $name;
  $u->password = $password;
  $errors = array_merge($u->validate(), $u->validatePassword($password2));

  if (!count($errors)) {
    $u->password = password_hash($password, PASSWORD_DEFAULT);
    $u->save();
    Session::login($u, false);
  }
  else{
    foreach($errors as $error){
      FlashMessage::add(implode("",$error));
    }
  }
}

$errors['email']='';
$errors['password']='';
$errors['username']='';
$errors['name']='';
$errors['password']='';
$errors['password2']='';

SmartyWrap::assign('method', $method);
SmartyWrap::assign('email', $email);
SmartyWrap::assign('username', $username);
SmartyWrap::assign('name', $name);
SmartyWrap::assign('remember', $remember);
SmartyWrap::assign('errors',$errors);
SmartyWrap::display('auth/login.tpl');

?>
