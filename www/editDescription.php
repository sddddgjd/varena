<?php 


require_once '../lib/Util.php';

$id=Request::get('id');
$user=User::get_by_id($id);
$submitButton=Request::get('submitButton');

if ($user != Session::getUser()){
  FlashMessage::add(_('You do not have permission to view this page'));
  Http::redirect('index');
}
$userDesc=UserDesc::get_by_userId($id);
if ($userDesc){
  SmartyWrap::assign('userDesc',$userDesc->description);
} else
  SmartyWrap::assign('userDesc',_('User does not have a description yet.'));

if ($submitButton){
	if ($userDesc){
		$userDesc->description = Request::get('description');
		$userDesc->html = $userDesc->getHtml();
	} else{
		$userDesc = Model::factory('UserDesc')->create();;
		$userDesc->userId = $id;
		$userDesc->description = Request::get('description');
		$userDesc->html = $userDesc->getHtml();
	}
	$userDesc->save();
	Http::redirect('user?id='.$id);
}
Smartywrap::assign('id',$id);
SmartyWrap::display('editDescription.tpl');
?>