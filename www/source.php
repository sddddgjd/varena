<?php

require_once '../lib/Util.php';

$id = Request::get('id');

$source = Source::get_by_id($id);

if (!$source) {
  FlashMessage::add(_('Source not found.'));
  Http::redirect(Util::$wwwRoot);
}

SmartyWrap::assign('s', $source);
SmartyWrap::display('source.tpl');
