<?php

namespace Epals {

require_once('EpalsLookupTable.php');

class Role {
  public $roleId;
  public $roleName;
}

class RoleLookup extends LookupTable {

  protected $endpoint_load = '/lookup/roles';
  protected $all_roles;
  protected $roles_by_id;
  protected $roles_by_name;

  function load()
  {
    parent::load();
    $roles = array();
    foreach ($this->data as $c)
    {
      $co = new Role();
      $co->roleName = $c->value;
      $co->roleId = $c->key;

      array_push($roles, $co); 
      $this->roles_by_id[$c->key] = $co;
      $this->roles_by_name[$c->value] = $c->key; 
    } 
    $this->all_roles = $roles;
  }

  function getAllRoles() {
    return $this->all_roles;
  }

  function getRoleName($role_id) {
    return isset($this->roles_by_id[$role_id]) ? $this->roles_by_id[$role_id]->roleName : null;
  }

  function getRoleId($role_name) {
    return isset($this->roles_by_name[$role_name]) ? $this->roles_by_name[$role_name] : null;
  }


}

}
