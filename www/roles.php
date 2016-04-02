<?php

require_once '../lib/Util.php';

$roles = Model::factory('Role')
          ->order_by_asc('name')
          ->find_many();

SmartyWrap::assign('roles', $roles);
SmartyWrap::display('roles.tpl');

?>
