<?php

require_once '../lib/Util.php';

$problems = Model::factory('Problem')
          ->order_by_asc('created')
          ->find_many();

$user = Session::getUser();
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

SmartyWrap::assign('problems', $problems);
SmartyWrap::assign('attempted',$attempted);
SmartyWrap::assign('solved',$solved);
SmartyWrap::assign('unsolved',$unsolved);
SmartyWrap::assign('user', $user);
if($user)
  SmartyWrap::assign('score',$score);
SmartyWrap::assign('canAdd', $user && $user->can(Permission::PERM_ADD_PROBLEM));
SmartyWrap::display('problems.tpl');

?>
