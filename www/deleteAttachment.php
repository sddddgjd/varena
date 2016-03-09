<?php

require_once '../lib/Util.php';

Util::requireLoggedIn();

$id = Request::get('id');

$a = Attachment::get_by_id($id);
if (!$a) {
  FlashMessage::add(_('Attachment not found.'));
  Util::redirect(Util::$wwwRoot);
}

$p = Problem::get_by_id($a->problemId);
$u = Session::getUser();

if (!$p->editableBy($u)) {
  FlashMessage::add(_('You cannot edit this problem.'));
  Util::redirect("problem.php?id={$p->id}");
}

@unlink($a->getFullPath());
$a->delete();

FlashMessage::add(_('Attachment deleted.'), 'success');
Util::redirect("attachments.php?id={$p->id}");

?>
