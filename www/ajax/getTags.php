<?php 

require_once '../../lib/Util.php';

$q = Request::get('q');

$tags = Model::factory('Tag')
      ->where_like('value', "%{$q}%")
      ->limit(10)
      ->find_many();

$data = [ 'results' => [] ];
foreach ($tags as $t) {
  $data['results'][] = [
    'id' => $t->id,
    'text' => $t->value,
  ];
}

print json_encode($data);
