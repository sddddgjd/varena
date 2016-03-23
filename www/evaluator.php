<?php

require_once '../lib/Util.php';

$sources = Model::factory('Source')
         ->order_by_desc('id')
         ->find_many();

SmartyWrap::assign('sources', $sources);
SmartyWrap::display('evaluator.tpl');

?>
