<?php

require_once '../lib/Util.php';

$rounds = Model::factory('Round')
        ->order_by_desc('start')
        ->find_many();

$user = Session::getUser();

SmartyWrap::assign('rounds', $rounds);
SmartyWrap::assign('canAdd', $user && $user->can(Permission::PERM_ROUND));
SmartyWrap::display('rounds.tpl');

?>
