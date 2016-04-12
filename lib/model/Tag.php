<?php

class Tag extends BaseObject {

  // fields populated during loadTree()
  public $canDelete = 1;
  public $children = [];

  static function loadByProblemId($problemId) {
    return Model::factory('Tag')
      ->select('Tag.*')
      ->join('ProblemTag', array('Tag.id', '=', 'tagId'))
      ->where('ProblemTag.problemId', $problemId)
      ->order_by_asc('rank')
      ->find_many();
  }

  // Returns an array of root tags with their $children and $canDelete fields populated
  static function loadTree() {
    $tags = Model::factory('Tag')->order_by_asc('rank')->find_many();

    // Map the tags by id
    $map = [];
    foreach ($tags as $t) {
      $map[$t->id] = $t;
    }

    // Mark tags which can be deleted
    $usedIds = Model::factory('ProblemTag')
             ->select('tagId')
             ->distinct()
             ->find_many();
    foreach ($usedIds as $rec) {
      $map[$rec->tagId]->canDelete = 0;
    }

    // Make each tag its parent's child
    foreach ($tags as $t) {
      if ($t->parentId) {
        $p = $map[$t->parentId];
        $p->children[$t->rank] = $t;
      }
    }

    // Return just the roots
    return array_filter($tags, function($t) {
      return !$t->parentId;
    });
  }
}

?>
