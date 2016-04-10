<?php 

require_once '../../lib/Util.php';

$q = Request::get('q');

$problems = Model::factory('Problem')
          ->where_like('name', "%{$q}%")
          ->limit(10)
          ->find_many();

$data = [ 'results' => [] ];
foreach ($problems as $p) {
  $data['results'][] = [
    'id' => $p->id,
    'text' => $p->name,
  ];
}

print json_encode($data);
