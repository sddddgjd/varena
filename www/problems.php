<?php

require_once '../lib/Util.php';

$problems = Model::factory('Problem')
          ->order_by_asc('created')
          ->find_many();

$user = Session::getUser();
$tab = Request::get('tab');
$page = Request::get('page');
$solved = array();
$attempted = array();
$unsolved = array();
$score = array();

if( !$tab)
  $tab = 1;
if (!$page)
  $page = 1;

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

switch($tab){
  case 1:
    $entries = count($problems);
    break;
  case 2:
    $entries = count($unsolved);
    break;
  case 3:
    $entries = count($attempted);
    break;
  case 4:
    $entries = count($solved);
    break;
}

$first = array();
$middle = array();
$last = array();
$displayNum = 25;
if ($entries) {
  $pages = floor (($entries-1)/$displayNum+1);
  if ($pages<12){
    for($i = 1; $i<=$pages; $i++)
      array_push($first,$i);
  } else{
   if ($page<=8)
      $lim = 10;
   else
     $lim = 6;
   for ($i = 1; $i<=$lim; $i++)
     array_push($first,$i);
   if($page>$pages-5)
     $lim = $pages-5;
   else
     $lim = $pages-3;
   for ($i = $lim; $i <= $pages; $i++)
     array_push($last,$i);
   if ($page > 8 && $page <= $pages-5){
     for ($i = $page-2; $i <= $page+2; $i++)
       array_push($middle,$i);
    }
  }
}

SmartyWrap::assign('problems', array_slice($problems, ($page-1)*$displayNum,$displayNum,true));
SmartyWrap::assign('attempted',array_slice($attempted, ($page-1)*$displayNum,$displayNum,true));
SmartyWrap::assign('solved',array_slice($solved, ($page-1)*$displayNum,$displayNum,true));
SmartyWrap::assign('unsolved',array_slice($unsolved, ($page-1)*$displayNum,$displayNum,true));
SmartyWrap::assign('first',$first);
SmartyWrap::assign('middle',$middle);
SmartyWrap::assign('last',$last);
SmartyWrap::assign('page',$page);
SmartyWrap::assign('tab',$tab);
SmartyWrap::assign('user', $user);
if($user)
  SmartyWrap::assign('score',$score);
SmartyWrap::assign('canAdd', $user && $user->can(Permission::PERM_ADD_PROBLEM));
SmartyWrap::display('problems.tpl');

?>
