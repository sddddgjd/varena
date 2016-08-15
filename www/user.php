<?php 


require_once '../lib/Util.php';

$id = Request::get('id');
$user = User::get_by_id($id);

if(!$user){
  FlashMessage::add(_('Invalid user.'));
  Http::redirect('index');
}
if($user==Session::getUser())
  SmartyWrap::assign('canEdit',1);
else
  SmartyWrap::assign('canEdit',0);
$userDesc=UserDesc::get_by_userId($id);
if($userDesc){
  $userDesc=UserDesc::get_by_userId($id);
  SmartyWrap::assign('userDesc',$userDesc->html);
} else
  SmartyWrap::assign('userDesc',_('User does not have a description yet.'));
SmartyWrap::assign('wwwRoot',Util::$wwwRoot);
SmartyWrap::assign('user', $user);
SmartyWrap::display('user.tpl');

?>
