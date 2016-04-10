<?php

require_once '../lib/Util.php';

$id = Request::get('id');

$round = Round::get_by_id($id);

if (!$round) {
  FlashMessage::add(_('Round not found.'));
  Http::redirect(Util::$wwwRoot);
}

$user = Session::getUser();

SmartyWrap::assign('round', $round);
SmartyWrap::assign('problems', $round->getProblems());
SmartyWrap::assign('canManage', $user && $user->can(Permission::PERM_ROUND));
SmartyWrap::display('round.tpl');

?>
