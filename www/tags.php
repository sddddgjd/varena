<?php

require_once '../lib/Util.php';

$jsonTags = Request::get('jsonTags');
$save = Request::isset('save');

if ($save) {
  Permission::enforce(Permission::PERM_TAGS, "problems.php");

  // Build a map of all MeaningTag IDs so we can delete those that are gone.
  $ids = Model::factory('Tag')
       ->select('id')
       ->find_array();

  $idMap = [];
  foreach($ids as $rec) {
    $idMap[$rec['id']] = 1;
  }

  // For each level, store (1) the last tag ID seen and (2) the current
  // number of children
  $tagIds = [ 0 ];
  $numChildren = [ 0 ];

  foreach (json_decode($jsonTags) as $rec) {
    if ($rec->id) {
      $tag = Tag::get_by_id($rec->id);
      unset($idMap[$rec->id]);
    } else {
      $tag = Model::factory('Tag')->create();
    }
    $tag->value = $rec->value;
    $tag->parentId = $tagIds[$rec->level - 1];
    $tag->rank = ++$numChildren[$rec->level - 1];
    $tag->save();
    $tagIds[$rec->level] = $tag->id;
    $numChildren[$rec->level] = 0;
  }

  foreach ($idMap as $id => $ignored) {
    Tag::delete_all_by_id($id);
  }
  Log::notice('Saved tag tree');
  FlashMessage::add(_('Tags saved.'), 'success');
  Http::redirect("tags.php");
}

SmartyWrap::assign('tags', Tag::loadTree());
SmartyWrap::display('tags.tpl');

?>
