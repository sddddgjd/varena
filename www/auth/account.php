<?php 

/**
 * Displays or edits the user's account information. Can be invoked in two situations:
 * - when a logged in user clicks on their "my account" link;
 * - when a user logs in using an OpenID not seen before, in which case they must choose a username
 **/

require_once '../../lib/Util.php';

$name = Request::get('name');
$email = Request::get('email');
$newPassword = Request::get('newpassword');
$newPassword2 = Request::get('newpassword2');
$password= Request::get('password');
$submitButton = Request::get('name');
$errors=[];

Util::requireLoggedIn();
$user = User::get_by_id(Session::getUser()->id);
// Save action
if ($submitButton && password_verify($password, $user->password)) {
  $user->name = $name;
  $user->email = $email;

  if ($newPassword){
    $user->password=$newPassword;
    $errors = array_merge($user->validate(), $user->validatePassword($newPassword2));
  }
  else
    $errors = $user->validate();
  
  if (!count($errors)) {
    $user->save();
    Session::set('user', $user); // cache the new values
    FlashMessage::add(_('Changes saved.'), 'info');
    Http::redirect('account');
  } else{
      foreach($errors as $error){
        FlashMessage::add(implode("",$error));
      }
  }
} else if($submitButton){
  FlashMessage::add(_('Incorrect password.'));
}

SmartyWrap::assign('editUser', $user);
SmartyWrap::display('auth/account.tpl');

?>
