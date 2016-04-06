<?php

require_once __DIR__ . '/../lib/Util.php';

if ($argc != 2) {
  die("Usage: {$argv[0]} <username>\n");
}

$u = User::get_by_username($argv[1]) or die("User not found.\n");

print "User {$u->username} (ID = {$u->id}) found.\n";

$role = Model::factory('Role')->create();
$role->name = _('god');
$role->save();
print "Created role '{$role->name}', ID = {$role->id}\n";

foreach (Permission::$NAMES as $perm => $name) {
  $rp = Model::factory('RolePermission')->create();
  $rp->roleId = $role->id;
  $rp->permission = $perm;
  $rp->save();
  print "Mapped role to permission '{$name}'\n";
}

$ur = Model::factory('UserRole')->create();
$ur->userId = $u->id;
$ur->roleId = $role->id;
$ur->save();
print "Mapped user to role\n";
