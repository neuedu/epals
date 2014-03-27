<?php

namespace Epals {

require_once('EpalsLookupTable.php');

class SchoolType {
  public $schoolTypeId;
  public $schoolTypeName;
}

class SchoolTypeLookup extends LookupTable {

  protected $endpoint_load = '/lookup/school-types';
  protected $all_school_types;
  protected $school_types_by_id;
  protected $school_types_by_name;

  function load()
  {
    parent::load();
    $school_types = array();
    foreach ($this->data as $c)
    {
      $co = new SchoolType();
      $co->schoolTypeName = $c->value;
      $co->schoolTypeId = $c->key;

      array_push($school_types, $co); 
      $this->school_types_by_id[$c->key] = $co;
      $this->school_types_by_name[$c->value] = $c->key; 
    } 
    $this->all_school_types = $school_types;
  }

  function getAllSchoolTypes() {
    return $this->all_school_types;
  }

  function getSchoolTypeName($school_type_id) {
    return isset($this->school_types_by_id[$school_type_id]) ? $this->school_types_by_id[$school_type_id]->schoolTypeName : null;
  }

  function getSchoolTypeId($school_type_name) {
    return isset($this->school_types_by_name[$school_type_name]) ? $this->school_types_by_name[$school_type_name] : null;
  }


}

}
