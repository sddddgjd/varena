<?php

/**
 * Take an email address and send out an email with a password change link.
 **/

require_once '../../lib/Util.php';

Util::requireNotLoggedIn();

$email = Request::get('email');
$errors['email']='';
if ($email) {
  if (!Config::get('email.enabled')) {
    FlashMessage::add(_('The system is configured not to send out any emails. Please contact an administrator.'));
    Http::redirect(Util::$wwwRoot);
  }

  $u = User::get_by_email($email);
  if ($u && Config::get('email.enabled')) {
    // Generate a token.
    EmailToken::delete_all_by_userId($u->id);
    $et = Model::factory('EmailToken')->create();
    $et->userId = $u->id;
    $et->token = bin2hex(random_bytes(EmailToken::LENGTH/2));
    $et->save();

    // Fill in the email template.
    SmartyWrap::assign('homePage', Util::getFullServerUrl());
    SmartyWrap::assign('token', $et->token);
    SmartyWrap::assign('signature', Config::get('email.signature'));
    SmartyWrap::assign('minutes', EmailToken::DURATION / 60);
    $body = SmartyWrap::fetchEmail('changePass.tpl');

    // Send out the email.
    $subject = _('Password change');
    $headers = [
      Config::get('email.fromHeader'),
      Config::get('email.replyToHeader'),
      'Content-Type: text/plain; charset=UTF-8',
    ];
    mail($email, $subject, $body, implode("\r\n", $headers));
  }

  // Always display a confirmation, whether or not the user exists.
  FlashMessage::add(
    sprintf(
      _("We have sent an email to <b>%s</b>. Click on the included link to change your password."),
      $email),
    'info');
  Http::redirect(Util::$wwwRoot);
}

SmartyWrap::assign('errors',$errors);
SmartyWrap::display('auth/changePass.tpl');

?>
