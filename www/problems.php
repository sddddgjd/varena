<?php

require_once '../lib/Util.php';

$problems = Model::factory('Problem')
          ->order_by_asc('name')
          ->find_many();

SmartyWrap::assign('problems', $problems);
SmartyWrap::display('problems.tpl');

?>
