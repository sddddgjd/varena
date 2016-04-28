<?php

require_once '../lib/Util.php';

$user = Session::getUser();
if (!$user) {
  Util::requireLoggedIn();
}

SmartyWrap::assign('roles', $user->can(Permission::PERM_ROLES));
SmartyWrap::assign('tags', $user->can(Permission::PERM_TAGS));

SmartyWrap::display('admin.tpl');

?>
