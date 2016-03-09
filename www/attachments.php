<?php

require_once '../lib/Util.php';

Util::requireLoggedIn();

$id = Request::get('id');

$problem = Problem::get_by_id($id);

if (!$problem) {
  FlashMessage::add(_('Problem not found.'));
  Util::redirect(Util::$wwwRoot);
}

$attachments = Model::factory('Attachment')
  ->where('problemId', $problem->id)
  ->order_by_asc('name')
  ->find_many();

SmartyWrap::assign('problem', $problem);
SmartyWrap::assign('attachments', $attachments);
SmartyWrap::display('attachments.tpl');

?>
