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
  if (!$user || !$user->can(Permission::PERM_ATTACHMENTS)) {
    FlashMessage::add(Permission::error(Permission::PERM_ATTACHMENTS));
    Http::redirect("attachments.php?id={$id}");
  }

  if (processUploads($files, $problem, $user)) {
    $msg = sprintf(_('%s file(s) uploaded successfully.'),
                   count($files['name']));
    FlashMessage::add($msg, 'success');
  }
  Http::redirect("attachments.php?id={$id}");
}

if ($delete) {
  deleteAttachments($user, $problem, $attachmentIds);
}

$attachments = Model::factory('Attachment')
  ->where('problemId', $problem->id)
  ->order_by_asc('name')
  ->find_many();

$data = [];
$massActions = false;
foreach ($attachments as $a) {
  if (StringUtil::StartsWith($a->name, Attachment::PREFIX_GRADER)) {
    $selectable = $user && $user->can(Permission::PERM_GRADER_ATTACHMENTS);
  } else {
    $selectable = true;
  }
  $massActions |= $selectable;
  
  $data[] = [
    'a' => $a,
    'selectable' => $selectable,
  ];
}

SmartyWrap::addJs('fileUpload');
SmartyWrap::assign('problem', $problem);
SmartyWrap::assign('data', $data);
SmartyWrap::assign('massActions', $massActions);
SmartyWrap::assign('permAddDelete', $user && $user->can(Permission::PERM_ATTACHMENTS));
SmartyWrap::display('attachments.tpl');

/**************************************************************************/

/* Returns false and sets flash messages on errors. */
function processUploads($files, $problem, $user) {
  // Multiple files are uploaded as [ 'name' => ['f1.txt', 'f2.txt'], 'type' => ... ]
  $count = count($files['name']);
  $success = true;

  // Check grader permissions if needed
  $grader = false;
  foreach ($files['name'] as $fileName) {
    $grader |= StringUtil::StartsWith($fileName, Attachment::PREFIX_GRADER);
  }
  if ($grader && !$user->can(Permission::PERM_GRADER_ATTACHMENTS)) {
    FlashMessage::add(Permission::error(Permission::PERM_GRADER_ATTACHMENTS));
    Http::redirect("attachments.php?id={$problem->id}");
  }

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

function deleteAttachments($user, $problem, $attachmentIds) {
  if (!$user || !$user->can(Permission::PERM_ATTACHMENTS)) {
    FlashMessage::add(Permission::error(Permission::PERM_ATTACHMENTS));
    Http::redirect("attachments.php?id={$problem->id}");
  }

  foreach ($attachmentIds as $aid) {
    $a = Attachment::get_by_id($aid);
    if (!$a || ($a->problemId != $problem->id)) {
      FlashMessage::add(_('Attachment not found or belongs to a different problem.'));
      Http::redirect("attachments.php?id={$problem->id}");
    }

    if (StringUtil::StartsWith($a->name, Attachment::PREFIX_GRADER) &&
        !$user->can(Permission::PERM_GRADER_ATTACHMENTS)) {
      FlashMessage::add(Permission::error(Permission::PERM_GRADER_ATTACHMENTS));
      Http::redirect("attachments.php?id={$problem->id}");
    }
  }

  // Checks passed, perform the actual deletion.
  foreach ($attachmentIds as $aid) {
    Attachment::delete_all_by_id($aid);
  }
  
  $msg = sprintf(_('%s attachment(s) deleted.'), count($attachmentIds));
  FlashMessage::add($msg, 'success');
  Http::redirect("attachments.php?id={$problem->id}");
}

?>
