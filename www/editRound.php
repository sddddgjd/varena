<?php

require_once '../lib/Util.php';

$id = Request::get('id');
$preview = Request::isset('preview');
$save = Request::isset('save');

if ($id) {
  $r = Round::get_by_id($id);
  if (!$r) {
    FlashMessage::add(_('Round not found.'));
    Http::redirect(Util::$wwwRoot);
  }

  Permission::enforce(Permission::PERM_ROUND, "round.php?id={$r->id}");
} else {
  Permission::enforce(Permission::PERM_ROUND, "rounds.php");

  $user = Session::getUser();
  $r = Model::factory('Round')->create();
  $r->userId = $user->id;
}

if ($save || $preview) {
  $r->name = Request::get('name');
  $r->description = Request::get('description');
  $r->start = Request::get('start');
  $r->duration = Request::get('duration');

  $errors = $r->validate();
  if ($errors) {
    SmartyWrap::assign('errors', $errors);
  }

  if ($save && !$errors) {
    $r->save();

    FlashMessage::add(_('Round saved.'), 'success');
    Http::redirect("round.php?id={$r->id}");
  } else if ($preview) {
    FlashMessage::add(_("This is only a preview. Don't forget to save your changes!"),
                      'warning');
    SmartyWrap::assign('previewed', true);
  }
}

SmartyWrap::assign('r', $r);
SmartyWrap::display('editRound.tpl');

?>
