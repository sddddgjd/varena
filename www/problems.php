<?php

require_once '../lib/Util.php';

$problems = Model::factory('Problem')
          ->order_by_asc('name')
          ->find_many();

$user = Session::getUser();

SmartyWrap::assign('problems', $problems);
SmartyWrap::assign('canAdd', $user && $user->can(Permission::PERM_ADD_PROBLEM));
SmartyWrap::display('problems.tpl');

?>
