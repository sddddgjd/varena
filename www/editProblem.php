<?php

require_once '../lib/Util.php';

Util::requireLoggedIn();

$id = Request::get('id');
$name = Request::get('name');
$statement = Request::get('statement');
$preview = Request::isset('preview');
$save = Request::isset('save');

$problem = Problem::get_by_id($id);
if (!$problem) {
  FlashMessage::add(_('Problem not found.'));
  Util::redirect(Util::$wwwRoot);
}

if (!$problem->editableBy(Session::getUser())) {
  FlashMessage::add(_('You cannot edit this problem.'));
  Util::redirect("problem.php?id={$id}");
}

if ($save || $preview) {
  $problem->name = $name;
  $problem->statement = $statement;

  $errors = $problem->validate();
  if ($errors) {
    SmartyWrap::assign('errors', $errors);
  }
  if ($save && !$errors) {
    $problem->save();
    Util::redirect("problem.php?id={$id}");
  } else if ($preview) { // preview
    SmartyWrap::assign('previewed', true);
  }
}

SmartyWrap::assign('problem', $problem);
SmartyWrap::display('editProblem.tpl');

?>
