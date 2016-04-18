<?php

require_once __DIR__ . '/../lib/Util.php';

if ($argc != 2) {
  die("Usage: {$argv[0]} <username>\n");
}

$ROLE_NAME = _('god');

$u = User::get_by_username($argv[1]) or die("User not found.\n");

print "User {$u->username} (ID = {$u->id}) found.\n";


$role = Role::get_by_name($ROLE_NAME);
if ($role) {
  print "Role '{$role->name}' already exists!\n";
} else {
  $role = Model::factory('Role')->create();
  $role->name = $ROLE_NAME;
  $role->save();
  print "Created role '{$role->name}', ID = {$role->id}\n";
}

foreach (Permission::$NAMES as $perm => $name) {
  $rp = RolePermission::get_by_permission($perm);
  if (!$rp or !($rp->roleId == $role->id)) {
    $rp = Model::factory('RolePermission')->create();
    $rp->roleId = $role->id;
    $rp->permission = $perm;
    $rp->save();
    print "Mapped role to permission '{$name}'\n";
  } else {
    print "Permission {$name} already exists and mapped to {$role->name}.\n";
  }
}

$ur = UserRole::get_by_roleId($role->id);

if (!$ur or !($ur->userId == $u->id)) {
  $ur = Model::factory('UserRole')->create();
  $ur->userId = $u->id;
  $ur->roleId = $role->id;
  $ur->save();
  print "Mapped user to role\n";
} else {
  print "User {$u->username} already mapped to {$role->name} role.\n";
}
