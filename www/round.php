<?php

require_once '../lib/Util.php';

$id = Request::get('id');

$round = Round::get_by_id($id);

if (!$round) {
  FlashMessage::add(_('Round not found.'));
  Http::redirect(Util::$wwwRoot);
}

// Collect problems and sources.
$problems = $round->getProblems();
$problemIds = array_keys($problems);
$sources = $round->getSources();

// Collect scores for each user and problem (recent scores overwrite old ones).
$table = [];
foreach ($sources as $s) {
  $problemOrder = array_search($s->problemId, $problemIds);
  $table[$s->userId][$problemOrder] = $s->score;
}

// Generate a printable scoreboard.
$scoreboard = [];
foreach ($table as $userId => $scores) {
  // Pad missing sources with null
  $filledScores = array_replace(array_fill(0, count($problems), null),
                                $scores);

  $scoreboard[] = [
    'user' => User::get_by_id($userId),
    'scores' => $filledScores,
    'total' => array_sum($scores),
  ];
}
usort($scoreboard, function($a, $b) {
  return $a['total'] < $b['total'];
});

$user = Session::getUser();

SmartyWrap::assign('round', $round);
SmartyWrap::assign('problems', $problems);
SmartyWrap::assign('scoreboard', $scoreboard);
SmartyWrap::assign('canManage', $user && $user->can(Permission::PERM_ROUND));
SmartyWrap::display('round.tpl');

?>
