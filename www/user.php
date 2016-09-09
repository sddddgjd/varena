<?php 


require_once '../lib/Util.php';

$id = Request::get('id');
$user = User::get_by_id($id);
$solved = Model::factory('Source') -> where('score',100) ->find_many();
$attempts = Model::factory('Source') -> where_lt('score',100) -> find_many();
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

$sProblems = array();
$aProblems = array();
 foreach ($solved as $s){
    $sProblems[$s->problemId] = Problem::get_by_id($s->problemId);
  }
foreach ($attempts as $s){
  if (!array_key_exists($s->problemId, $sProblems))
    array_push($aProblems,Problem::get_by_id($s->problemId));
}

SmartyWrap::assign('wwwRoot',Util::$wwwRoot);
SmartyWrap::assign('user', $user);
SmartyWrap::assign('solved',$sProblems);
SmartyWrap::assign('attempts',$aProblems);
SmartyWrap::display('user.tpl');

?>
