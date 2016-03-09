<?php

require_once '../lib/Util.php';

$id = Request::get('id');

$problem = Problem::get_by_id($id);

SmartyWrap::assign('problem', $problem);
SmartyWrap::display('problem.tpl');

?>
