<?php

require_once '../lib/Util.php';

Util::requireLoggedIn();

$id = Util::get('id');
$name = Util::get('name');
$statement = Util::get('statement');

$problem = Problem::get_by_id($id);

SmartyWrap::assign('problem', $problem);
SmartyWrap::display('editProblem.tpl');

?>
