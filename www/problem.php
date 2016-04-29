<?php

require_once '../lib/Util.php';

$id = Request::get('id');
$file = Request::getFiles('source');

$problem = Problem::get_by_id($id);
$user = Session::getUser();

if (!$problem) {
  FlashMessage::add(_('Problem not found.'));
  Http::redirect(Util::$wwwRoot);
}

// Make the problem public if it is part of an ongoing round.
if ($problem->visibility == Problem::VIS_PRIVATE) {
  $timestamp = time();
  $r = Model::factory('Round')
     ->table_alias('r')
     ->join('round_problem', [ 'r.id', '=', 'rp.roundId' ], 'rp')
     ->where('rp.problemId', $problem->id)
     ->where_lte('r.start', $timestamp)
     ->where_raw('(? <= r.start + r.duration * 60)', [ $timestamp ])
     ->find_one();
  if ($r) {
    $problem->visibility = Problem::VIS_PUBLIC;
    $problem->save();
  }
}

if (!$problem->viewableBy($user)) {
  FlashMessage::add(_('Permission denied to view this problem.'));
  Http::redirect(Util::$wwwRoot);
}

if ($file) {
  if (!$user) {
    Util::requireLoggedIn();
  }

  if (processUploads($file, $problem, $user)) {
    FlashMessage::add(_('Source file uploaded successfully.'), 'success');
  }
  Http::redirect("problem.php?id={$problem->id}");
}

// Load the most recent source from the user
$user = Session::getUser();
if ($user) {
  $source = Model::factory('Source')
          ->where('problemId', $problem->id)
          ->where('userId', $user->id)
          // Keep in sync with Source->hasScore()
          ->where_in('status', Source::$STATUSES_WITH_SCORE)
          ->order_by_desc('created')
          ->find_one();
  $score = $source ? $source->score : null;
} else {
  $score = null;
}

SmartyWrap::assign('problem', $problem);
SmartyWrap::assign('tags', $problem->getTags());
SmartyWrap::assign('score', $score);
SmartyWrap::addJs('fileUpload');
SmartyWrap::display('problem.tpl');

/**************************************************************************/

/* Returns false and sets flash messages on errors. */
function processUploads($file, $problem, $user) {

  if ($file['error'] != UPLOAD_ERR_OK) {
    FlashMessage::add(sprintf(_('There was a problem uploading «%s».'),
                              $file['name']));
    return false;
  }

  $s = Model::factory('Source')->create();
  $s->problemId = $problem->id;
  $s->userId = $user->id;
  $s->sourceCode = file_get_contents($file['tmp_name']);
  $s->extension = mb_strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  $s->status = Source::STATUS_NEW;

  if (!in_array($s->extension, Source::$ACCEPTED_EXTENSIONS)) {
    FlashMessage::add(sprintf(_('Unknown extension «.%s».'),
                              $s->extension));
    return false;
  }

  $s->save();

  // Notify the evaluator
  Util::notifyEvaluator($s);

  return true;
}

?>
