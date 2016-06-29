<?php

require_once '../lib/Util.php';

$id = Request::get('id');
$generate = Request::isset('generate');
$preview = Request::isset('preview');
$save = Request::isset('save');

$user = Session::getUser();

if ($id) {
  $p = Problem::get_by_id($id);
  if (!$p) {
    FlashMessage::add(_('Problem not found.'));
    Http::redirect(Util::$wwwRoot);
  }

  if (!$p->editableBy($user)) {
    FlashMessage::add(_('You cannot edit this problem.'));
    Http::redirect("problem.php?id={$id}");
  }
} else {
  Permission::enforce(Permission::PERM_ADD_PROBLEM, "problems.php");

  $p = Model::factory('Problem')->create();
  $p->userId = $user->id;
}

if ($generate || $save || $preview) {
  $origDir = $p->getAttachmentDir();
  $p->name = Request::get('name');
  $p->statement = Request::get('statement');
  $p->numTests = Request::get('numTests');
  $p->testGroups = Request::get('testGroups');
  $p->hasWitness = Request::isset('hasWitness');
  $p->grader = Request::get('grader');
  $p->timeLimit = Request::get('timeLimit');
  $p->memoryLimit = Request::get('memoryLimit');
  $p->author = Request::get('authorName');
  $p->visibility = Request::get('visibility');
  $p->publicSources = Request::get('publicSources');
  $p->publicTests = Request::get('publicTests');
  $p->feedbackTests = Request::get('feedbackTests');
  $p->contest = Request::get('contest');
  $p->year = Request::get('year');
  $p->grade = Request::get('grade');
  $tagIds = Request::get('tagIds', []);

  if ($generate) {
    SmartyWrap::assign('p', $p);
    $p->statement = SmartyWrap::fetch('textile/problem.tpl');
  } else { // preview / save
    $errors = $p->validate();
    if ($errors) {
      SmartyWrap::assign('errors', $errors);
    }
    if ($save && !$errors) {
      $dir = $p->getAttachmentDir();
      if ($p->id && ($dir != $origDir)) {
        @rename($origDir, $dir); // may not exist yet
        FlashMessage::add(_('The problem name has changed. Remember to update any statement references (file names, attachments).'), 'warning');
      }

      $p->save();

      // delete old ProblemTag entries and save new ones
      ProblemTag::delete_all_by_problemId($p->id);
      foreach ($tagIds as $i => $tid) {
        $pt = Model::factory('ProblemTag')->create();
        $pt->problemId = $p->id;
        $pt->tagId = $tid;
        $pt->rank = $i;
        $pt->save();
      }

    FlashMessage::add(_('Problem saved.'), 'success');
      Http::redirect("problem.php?id={$p->id}");
    } else if ($preview) { // preview
      SmartyWrap::assign('previewed', true);
    }

    // Propagate the tag IDs
    $tags = array_map('Tag::get_by_id', $tagIds);
    SmartyWrap::assign('tags', $tags);
  }
} else {
  SmartyWrap::assign('tags', $p->getTags());
}

SmartyWrap::assign('p', $p);
SmartyWrap::addCss('select2');
SmartyWrap::addJs('select2');
SmartyWrap::display('editProblem.tpl');

?>
