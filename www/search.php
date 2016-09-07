<?php

$searchMinLength = 4;
$searchMaxLength = 30;
$nameScore = 30;
$authorScore = 20;
$tagScore = 10;
$statementScore = 1;
$searchQueries;

function validateSearch($search){
  if (strlen($search) > $GLOBALS['searchMaxLength']) {
    FlashMessage::add(_('Search term too long'));
    Http::redirect('index.php');
  }

  if(strlen($search) < $GLOBALS['searchMinLength']) {
   FlashMessage::add(_('Search term too short'));
   Http::redirect('index.php');
  }

  foreach($GLOBALS['searchQueries'] as $key => $query) {
    if (strlen($query) < $GLOBALS['searchMinLength']) {
      unset($GLOALS['searchQueries[$key]']);
    }
  }

  if (!count($GLOBALS['searchQueries'])) {
    FlashMessage::add(_('Search words too short'));
    Http::redirect('index.php');
  }
}

function printResults($scores){
  arsort($scores);
  $results = [];
  foreach($scores as $key => $score) array_push($results, Problem::get_by_id($key));
  SmartyWrap::assign('results', $results);
  SmartyWrap::display('search.tpl');
}

function searchQuery($pdo){
  $scores = array();
  $searchFields = ['name', 'author', 'statement'];
  $fieldScore = array_combine($searchFields, array(
    $GLOBALS['nameScore'],
    $GLOBALS['authorScore'],
    $GLOBALS['statementScore']
  ));
  foreach($GLOBALS['searchQueries'] as $eachQuery) {
    foreach($searchFields as $field) {
      $problems = $pdo->query("SELECT * FROM problem WHERE $field LIKE '%$eachQuery%'");
      foreach($problems as $problem) @$scores[$problem['id']]+= $fieldScore[$field];
    }

    $problems = $pdo->query("SELECT * FROM tag WHERE value LIKE '%$eachQuery%'");
    foreach($problems as $problem) {
      $id = $problem['id'];
      $newQuery = "SELECT * FROM problem_tag WHERE tagId = $id";
      $problemTags = $pdo->query($newQuery);
      foreach($problemTags as $problemTag) @$scores[$problemTag['problemId']]+= $GLOBALS['tagScore'];
    }
  }

  printResults($scores);
}

require_once '../lib/Util.php';

$searchMinLength = Config::get('search.searchMinLength');
$searchMaxLength = Config::get('search.searchMaxLength');
$nameScore = Config::get('search.nameScore');
$authorScore = Config::Get('search.authorScore');
$tagScore = Config::get('search.tagScore');
$statementScore = Config::get('search.statementScore');

$search = Request::get('search');
$searchQueries = explode(" ", $search);
validateSearch($search);
$dsn = Db::parseDsn(Config::get('general.database'));
$pdo = new PDO('mysql:host=' . $dsn['host'] . ';dbname=varena', $dsn['user'], $dsn['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
searchQuery($pdo);

?>
