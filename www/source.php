<?php

require_once '../lib/Util.php';

$id = Request::get('id');

$s = Source::get_by_id($id);

if (!$s) {
  FlashMessage::add(_('Source not found.'));
  Http::redirect(Util::$wwwRoot);
}

$format = EvalUtil::COMPILERS[$s->extension];
$command = sprintf($format, "file.{$s->extension}", "file");

$groups = $s->computeScore();

$data = [];
foreach ($tests as $t) {
  $data[$t->number] = [
    'runningTime' => $t->runningTime,
    'memoryUsed' => $t->memoryUsed,
    'message' => $t->message,
    'score' => $points[$t->number] * $t->score / 100,
    'rowSpan' => 0,
    'groupScore' => 0,
  ];
}

foreach ($groups as $gr) {
  $data[$gr['first']]['rowSpan'] = $gr['last'] - $gr['first'] + 1;
  $data[$gr['first']]['groupScore'] = $gr['score'];
}

SmartyWrap::assign('s', $s);
SmartyWrap::assign('problem', $s->getProblem());
SmartyWrap::assign('command', $command);
SmartyWrap::assign('data', $data);
SmartyWrap::display('source.tpl');
