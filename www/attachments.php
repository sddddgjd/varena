<?php

require_once '../lib/Util.php';

$id = Request::get('id');
$files = Request::getFiles('files');
$attachmentIds = Request::get('attachmentIds', []);
$download = Request::isset('download');
$delete = Request::isset('delete');

$problem = Problem::get_by_id($id);
$user = Session::getUser();

if (!$problem) {
  FlashMessage::add(_('Problem not found.'));
  Http::redirect(Util::$wwwRoot);
}

if ($files) {
  if (!$problem->editableBy($user)) {
    FlashMessage::add(_('You cannot edit this problem.'));
    Http::redirect("problem.php?id={$id}");
  }

  if (processUploads($files, $problem, $user)) {
    $msg = sprintf(_('%s file(s) uploaded successfully.'),
                   count($files['name']));
    FlashMessage::add($msg, 'success');
  }
  Http::redirect("attachments.php?id={$id}");
}

if ($delete) {
  if (!$problem->editableBy($user)) {
    FlashMessage::add(_('You cannot edit this problem.'));
    Http::redirect("problem.php?id={$id}");
  }
  foreach ($attachmentIds as $aid) {
    Attachment::delete_all_by_id($aid);
  }
  
  $msg = sprintf(_('%s attachment(s) deleted.'), count($attachmentIds));
  FlashMessage::add($msg, 'success');
  Http::redirect("attachments.php?id={$id}");
}

$attachments = Model::factory('Attachment')
  ->where('problemId', $problem->id)
  ->order_by_asc('name')
  ->find_many();

$massActions = $problem->editableBy($user) ||
             $problem->testsViewableBy($user);

SmartyWrap::addJs('fileUpload');
SmartyWrap::assign('problem', $problem);
SmartyWrap::assign('attachments', $attachments);
SmartyWrap::assign('massActions', $massActions);
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

      // Delete other attachments by the same name, for the same problem
      Attachment::delete_all_by_problemId_name($a->problemId, $a->name);

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
