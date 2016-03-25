<?php

require_once '../lib/Util.php';

$userId = Request::get('userId');
$problemId = Request::get('problemId');

$sources = Model::factory('Source');

if ($userId) {
  $sources = $sources->where('userId', $userId);
}

if ($problemId) {
  $sources = $sources->where('problemId', $problemId);
}

$sources = $sources
         ->order_by_desc('id')
         ->find_many();

SmartyWrap::assign('sources', $sources);
SmartyWrap::display('evaluator.tpl');

?>
