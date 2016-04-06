<?php

Permission::init();

/**
 * A list of granular permissions.
 * DO NOT EVER change values of existing constants. That could grant users
 * unintended permissions. Just add new values and number them sequentially.
 **/
class Permission {

  // increment this when adding new permissions
  const NUM_PERMISSIONS = 8;

  // permission management
  const PERM_ROLES = 1; // add/rename roles, delete unused roles
  const PERM_MAP_USER_ROLE = 2;
  const PERM_MAP_ROLE_PERMISSION = 3;

  // problems
  const PERM_ADD_PROBLEM = 4;
  const PERM_EDIT_PROBLEM = 5;  // TODO - until the problem is published or something
  const PERM_DELETE_PROBLEM = 6;

  // attachments
  const PERM_ATTACHMENTS = 7;
  const PERM_GRADER_ATTACHMENTS = 8; // view/add/delete grader_* files, regardless of problem status

  static $NAMES = null;
  static $GROUPS = null;

  static function init() {
    self::$NAMES = [
      self::PERM_ROLES => _('add/rename roles, delete unused roles'),
      self::PERM_MAP_USER_ROLE => _('map users to roles'),
      self::PERM_MAP_ROLE_PERMISSION => _('map roles to permissions'),

      self::PERM_ADD_PROBLEM => _('add problems'),
      self::PERM_EDIT_PROBLEM => _('edit problems'),
      self::PERM_DELETE_PROBLEM => _('delete problems'),

      self::PERM_ATTACHMENTS => _('add/delete attachments'),
      self::PERM_GRADER_ATTACHMENTS => _('view/add/delete grader_* attachments'),
    ];

    self::$GROUPS = [
      _('permission management') => [
        self::PERM_ROLES,
        self::PERM_MAP_USER_ROLE,
        self::PERM_MAP_ROLE_PERMISSION,
      ],
      _('problem management') => [
        self::PERM_ADD_PROBLEM,
        self::PERM_EDIT_PROBLEM,
        self::PERM_DELETE_PROBLEM,
      ],
      _('attachment management') => [
        self::PERM_ATTACHMENTS,
        self::PERM_GRADER_ATTACHMENTS,
      ],
    ];

    if (count(self::$NAMES) != self::NUM_PERMISSIONS) {
      die("Inconsistent values in Permission.php between NUM_PERMISSIONS and \$NAMES.\n");
    }

    // COUNT_RECURSIVE also counts the first-level keys
    if (count(self::$GROUPS, COUNT_RECURSIVE) - count(self::$GROUPS) !=
        self::NUM_PERMISSIONS) {
      die("Inconsistent values in Permission.php between NUM_PERMISSIONS and \$GROUPS.\n");
    }
  }

  static function getName(int $perm) {
    return self::$NAMES[$perm];
  }

  static function enforce($user, $perm, $target) {
    if (!$user || !$user->can($perm)) {
      $msg = sprintf(_('Missing permission: %s'), self::getName($perm));
      FlashMessage::add($msg);
      Http::redirect($target);
    }
  }
}
