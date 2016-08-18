<?php

class Search
{
  
  public static $searchMinLength = 4;
  public static $searchMaxLength = 30;
  
  public static $nameScore = 30;
  public static $authorScore = 20;
  public static $tagScore = 10;
  public static $statementScore = 1;
  
  private static $searchQueries;

  static function validateSearch($search){

    if (strlen($search) > self::$searchMaxLength) {
      FlashMessage::add(_('Search term too long'));
      Http::redirect('index.php');
    }
    
    foreach (self::$searchQueries as $key=>$query){
      if (strlen($query) < self::$searchMinLength) {
        unset(self::$searchQueries[$key]);
      }
    }

    if (!count(self::$searchQueries)) {
      FlashMessage::add(_('Search words too short'));
      Http::redirect('index.php');
    }
  }
  
  static function printResults($scores){
    arsort($scores);
    $results = [];

    foreach ($scores as $key=>$score)
      array_push($results,Problem::get_by_id($key));

    SmartyWrap::assign('results',$results);
    SmartyWrap::display('search.tpl');
  }

  static function searchQuery($pdo) {
    $scores = array();
    $searchFields = ['name','author','statement'];
    $fieldScore = array_combine($searchFields, array(self::$nameScore, self::$authorScore, self::$statementScore));

    foreach (self::$searchQueries as $eachQuery) {
      foreach ($searchFields as $field) {
        $problems = $pdo->query("SELECT * FROM problem WHERE $field LIKE '%$eachQuery%'");

        foreach ($problems as $problem)
          @$scores[$problem['id']] += $fieldScore[$field];
      }
      
      $problems = $pdo->query("SELECT * FROM tag WHERE value LIKE '%$eachQuery%'");

      foreach ($problems as $problem) {
        $id = $problem['id'];
        $newQuery = "SELECT * FROM problem_tag WHERE tagId = $id";
        $problemTags = $pdo->query($newQuery);

        foreach ($problemTags as $problemTag)
          @$scores[$problemTag['problemId']] += self::$tagScore;
      }
    }

    self::printResults($scores);
  }
  
  static function init() {
    require_once '../lib/Util.php';

    $search = Request::get('search');
    self::$searchQueries = explode(" ", $search);
    self::validateSearch($search);

    $dsn = Db::parseDsn(Config::get('general.database'));
    $pdo = new PDO('mysql:host=' . $dsn['host'] . ';dbname=varena', $dsn['user'], $dsn['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    self::searchQuery($pdo);
  }
}

Search::init();
?>