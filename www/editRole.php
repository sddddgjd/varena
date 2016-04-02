<?php

require_once '../lib/Util.php';

Util::requireLoggedIn();

$id = Request::get('id');
$save = Request::isset('save');

$user = Session::getUser();

if ($id) {
  $role = Role::get_by_id($id);
  if (!$role) {
    FlashMessage::add(_('Role not found.'));
    Http::redirect(Util::$wwwRoot);
  }
} else {
  $role = Model::factory('Role')->create();
}

if ($save) {
  // populate the role and create role permissions from request
  $role->name = Request::get('name');
  $permissions = Request::get('permissions', []);

  $rps = [];
  foreach ($permissions as $perm) {
    $rp = Model::factory('RolePermission')->create();
    $rp->permission = $perm;
    $rps[] = $rp;
  }

  $errors = $role->validate();
  if ($errors) {
    SmartyWrap::assign('errors', $errors);
  } else {
    // save the role
    $role->save();

    // delete previous role permissions
    RolePermission::delete_all_by_roleId($role->id);

    // save role permissions
    foreach ($rps as $rp) {
      $rp->roleId = $role->id;
      $rp->save();
    }

    FlashMessage::add(_('Role saved.'), 'success');
    Http::redirect('roles.php');
  }
} else {
  // load existing RolePermissions
  $rps = RolePermission::get_all_by_roleId($id);
}

// build a hash table of permissions
$permTable = [];
foreach ($rps as $rp) {
  $permTable[$rp->permission] = true;
}

// collect the permission data to display
$data = [];
foreach (Permission::$GROUPS as $groupName => $groupPerms) {
  $group = [];
  foreach ($groupPerms as $perm) {
    $group[] = [
      'perm' => $perm,
      'name' => Permission::getName($perm),
      'checked' => array_key_exists($perm, $permTable),
    ];
  }
  $data[$groupName] = $group;
}

// users having this role
if ($role->id) {
  $users = Model::factory('User')
         ->select('user.*')
         ->join('user_role', array('user_role.userId', '=', 'user.id'))
         ->where('user_role.roleId', $role->id)
         ->find_many();
  SmartyWrap::assign('users', $users);
}

SmartyWrap::assign('role', $role);
SmartyWrap::assign('data', $data);
SmartyWrap::display('editRole.tpl');

?>
