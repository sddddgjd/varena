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
  const PERM_ROLES = 1;

  // problems
  const PERM_ADD_PROBLEM = 2;
  const PERM_EDIT_PROBLEM = 3;  // TODO - until the problem is published or something
  const PERM_DELETE_PROBLEM = 4;

  // attachments
  const PERM_ATTACHMENTS = 5;
  const PERM_GRADER_ATTACHMENTS = 6; // view/add/delete grader_* files, regardless of problem status

  // rounds
  const PERM_ROUND = 7;

  // tags
  const PERM_TAGS = 8;

  static $NAMES = null;
  static $GROUPS = null;

  static function init() {
    self::$NAMES = [
      self::PERM_ROLES => _('manage user roles and permissions'),

      self::PERM_ADD_PROBLEM => _('add problems'),
      self::PERM_EDIT_PROBLEM => _('edit problems'),
      self::PERM_DELETE_PROBLEM => _('delete problems'),

      self::PERM_ATTACHMENTS => _('add/delete attachments'),
      self::PERM_GRADER_ATTACHMENTS => _('view/add/delete grader_* attachments'),

      self::PERM_ROUND => _('add/edit/delete rounds'),

      self::PERM_TAGS => _('add/edit/delete tags'),
    ];

    self::$GROUPS = [
      _('permission management') => [
        self::PERM_ROLES,
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
      _('round management') => [
        self::PERM_ROUND,
      ],
      _('tag management') => [
        self::PERM_TAGS,
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

  static function enforce($perm, $target) {
    $user = Session::getUser();

    if (!$user || !$user->can($perm)) {
      $msg = sprintf(_('Missing permission: %s'), self::getName($perm));
      FlashMessage::add($msg);
      Http::redirect($target);
    }
  }
}
