<?php

require_once '../../lib/Util.php';

$q = Request::get('q');

$problems = Model::factory('Problem')
          ->select('author')
          ->distinct()
          ->where_like('author', "%{$q}%")
          ->limit(10)
          ->find_many();

$data = [ 'results' => [] ];
foreach ($problems as $p) {
  $author = [
    'id' => $p->author,
    'text' => $p->author,
  ];
  $data['results'][] = $author;
}

print json_encode($data);
