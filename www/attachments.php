<?php

require_once '../lib/Util.php';

Util::requireLoggedIn();

$id = Request::get('id');
$files = Request::getFiles('files');

$problem = Problem::get_by_id($id);
$user = Session::getUser();

if (!$problem) {
  FlashMessage::add(_('Problem not found.'));
  Util::redirect(Util::$wwwRoot);
}

if (!$problem->editableBy($user)) {
  FlashMessage::add(_('You cannot edit this problem.'));
  Util::redirect("problem.php?id={$id}");
}

if ($files) {
  if (processUploads($files, $problem, $user)) {
    $msg = sprintf(_('%s file(s) uploaded successfully.'),
                   count($files['name']));
    FlashMessage::add($msg, 'info');
  }
  Util::redirect("attachments.php?id={$id}");
}

$attachments = Model::factory('Attachment')
  ->where('problemId', $problem->id)
  ->order_by_asc('name')
  ->find_many();

SmartyWrap::assign('problem', $problem);
SmartyWrap::assign('attachments', $attachments);
SmartyWrap::display('attachments.tpl');

/**************************************************************************/

/* Returns false and sets flash messages on errors. */
function processUploads($files, $problem, $user) {
  // Multiple files are uploaded as [ 'name' => ['f1.txt', 'f2.txt'], 'type' => ... ]
  $count = count($files['name']);
  $success = true;

  for ($i = 0; $i < $count; $i++) {
    $e = true; // Assume error until the end

    if ($files['error'][$i] == UPLOAD_ERR_OK) {

      $a = Model::factory('Attachment')->create();
      $a->problemId = $problem->id;
      $a->userId = $user->id;
      $a->name = $files['name'][$i];
      $a->size = $files['size'][$i];

      $path = $a->getFullPath();
      if (!file_exists(dirname($path))) {
        mkdir(dirname($path));
      }
      if (move_uploaded_file($files['tmp_name'][$i], $path)) {
        $a->save();
        $e = false;
      }
    }

    if ($e) {
      FlashMessage::add(sprintf(_('There was a problem uploading «%s».'),
                                $files['name'][$i]));
      $success = false;
    }
  }
  return $success;
}

?>
