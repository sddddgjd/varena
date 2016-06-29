<?php
require_once '../lib/Util.php';

Permission::enforce(Permission::PERM_ROLES, "index.php");

$userRoles = Model::factory('UserRole')->order_by_asc('userId')->find_many();
$roles = Model::factory('Role')->find_many();
$save = Request::isset('save');

if (Request::isset('delete')) {
  $userRole = UserRole::get_by_id(Request::get('delete'));
  $userRole->delete();
  FlashMessage::add(_('User role deleted.'), 'success');
  Http::redirect('userRoles.php');
}

if ($save) {
  $user = User::get_by_username(Request::get('username'));
  if ($user) {
    $newUserRole = Model::factory('UserRole')->create();
    $newUserRole->userId = $user->id;
    $newUserRole->roleId = Request::get('role');
    $newUserRole->save();
    FlashMessage::add(_('User Role saved.'), 'success');
  } else
      FlashMessage::add(_('User not found.'));
  Http::redirect('userRoles.php');
}

SmartyWrap::assign('userRoles', $userRoles);
SmartyWrap::assign('roles',$roles);
SmartyWrap::display('userRoles.tpl');
?>