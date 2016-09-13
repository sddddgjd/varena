<?php

require_once '../lib/Util.php';

$problems = Model::factory('Problem')
          ->order_by_asc('created')
          ->find_many();

$user = Session::getUser();
$page = Request::get('page');
$solved = array();
$attempted = array();
$unsolved = array();
$score = array();
if ($user){
  foreach ($problems as $key=>$p){
  	$score[$key] = Model::factory('Source') -> where('userId',$user->id) -> where('problemId',$p->id) ->order_by_desc('created')->find_one();
  	if ($score[$key]){
  	  if($score[$key]->score == 100)
  	  	$solved[$key] = $p;
  	  else
  	    $attempted[$key] = $p;
  	} else 
  	    $unsolved[$key] = $p;
  }
}
$entries['problems'] = count($problems);
$entries['unsolved'] = count($unsolved);
$entries['attempted'] = count($attempted);
$entries['solved'] = count($solved);
SmartyWrap::assign('problems', array_slice($problems,($page-1)*5,5,$preserve_keys=TRUE));
SmartyWrap::assign('attempted',array_slice($attempted,($page-1)*5,5,$preserve_keys=TRUE));
SmartyWrap::assign('solved',array_slice($solved,($page-1)*5,5,$preserve_keys=TRUE));
SmartyWrap::assign('unsolved',array_slice($unsolved,($page-1)*5,5,$preserve_keys=TRUE));
SmartyWrap::assign('page',$page);
SmartyWrap::assign('entries',$entries);
SmartyWrap::assign('user', $user);
if($user)
  SmartyWrap::assign('score',$score);
SmartyWrap::assign('canAdd', $user && $user->can(Permission::PERM_ADD_PROBLEM));
SmartyWrap::display('problems.tpl');

?>
