<?php

require_once '../lib/Util.php';

Util::requireLoggedIn();

$id = Request::get('id');
$preview = Request::isset('preview');
$save = Request::isset('save');

$user = Session::getUser();

if ($id) {
  $problem = Problem::get_by_id($id);
  if (!$problem) {
    FlashMessage::add(_('Problem not found.'));
    Http::redirect(Util::$wwwRoot);
  }

  if (!$problem->editableBy($user)) {
    FlashMessage::add(_('You cannot edit this problem.'));
    Http::redirect("problem.php?id={$id}");
  }
} else {
  $problem = Model::factory('Problem')->create();
  $problem->userId = $user->id;
}

if ($save || $preview) {
  $origDir = $problem->getAttachmentDir();
  $problem->name = Request::get('name');
  $problem->statement = Request::get('statement');
  $problem->numTests = Request::get('numTests');
  $problem->testGroups = Request::get('testGroups');
  $problem->hasWitness = Request::isset('hasWitness');
  $problem->grader = Request::get('grader');
  $problem->timeLimit = Request::get('timeLimit');
  $problem->memoryLimit = Request::get('memoryLimit');

  $errors = $problem->validate();
  if ($errors) {
    SmartyWrap::assign('errors', $errors);
  }
  if ($save && !$errors) {
    $dir = $problem->getAttachmentDir();
    if ($problem->id && ($dir != $origDir)) {
      @rename($origDir, $dir); // may not exist yet
      FlashMessage::add(_('The problem name has changed. Remember to update any markdown references to attachments.'), 'warning');
    }
    
    $problem->save();

    FlashMessage::add(_('Problem saved.'), 'success');
    Http::redirect("problem.php?id={$problem->id}");
  } else if ($preview) { // preview
    SmartyWrap::assign('previewed', true);
  }
}

SmartyWrap::assign('problem', $problem);
SmartyWrap::display('editProblem.tpl');

?>
