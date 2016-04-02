<?php

class Role extends BaseObject {

  /**
   * Validates a role for correctness. Returns an array of { field => array of errors }.
   **/
  function validate() {
    $id = $this->id ? $this->id : 0; // roles have no ID during creation

    $errors = [];

    if (mb_strlen($this->name) < 3) {
      $errors['name'][] = _('The name must be at least three characters long.');
    }

    $otherRole = Model::factory('Role')
               ->where('name', $this->name)
               ->where_not_equal('id', $id)
               ->find_one();
    if ($otherRole) {
      $errors['name'][] = _('There already exists a role by this name.');
    }

    return $errors;
  }

  function delete() {
    // delete dependent UserRoles
    $urs = UserRole::get_all_by_roleId($this->id);
    foreach ($urs as $ur) {
      $ur->delete();
    }

    // delete dependent RolePermissions
    $rps = RolePermission::get_all_by_roleId($this->id);
    foreach ($rps as $rp) {
      $rp->delete();
    }

    return parent::delete();
  }
}

?>
