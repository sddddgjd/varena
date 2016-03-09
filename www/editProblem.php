<?php

require_once '../lib/Util.php';

Util::requireLoggedIn();

$id = Util::get('id');
$name = Util::get('name');
$statement = Util::get('statement');
$save = Util::get('save');

$problem = Problem::get_by_id($id);
if (!$problem) {
  FlashMessage::add(_('Problem not found.'));
  Util::redirect(Util::$wwwRoot);
}

if (!$problem->editableBy(Session::getUser())) {
  FlashMessage::add(_('You cannot edit this problem.'));
  Util::redirect("problem.php?id={$id}");
}

if ($save) {
  $problem->name = $name;
  $problem->statement = $statement;

  $errors = $problem->validate();
  if (!$errors) {
    $problem->save();
    Util::redirect("problem.php?id={$id}");
  } else {
    SmartyWrap::assign('errors', $errors);
  }
}

SmartyWrap::assign('problem', $problem);
SmartyWrap::display('editProblem.tpl');

?>
