<?php

namespace Epals {

require_once('EpalsLookupTable.php');

class Grade {
  public $gradeId;
  public $gradeName;
}

class GradeLookup extends LookupTable {

  protected $endpoint_load = '/lookup/grades';
  protected $all_grades;
  protected $grades_by_id;
  protected $grades_by_name;

  function load()
  {
    parent::load();
    $grades = array();
    foreach ($this->data as $c)
    {
      $co = new Grade();
      $co->gradeName = $c->value;
      $co->gradeId = $c->key;

      array_push($grades, $co); 
      $this->grades_by_id[$c->key] = $co;
      $this->grades_by_name[$c->value] = $c->key; 
    } 
    $this->all_grades = $grades;
  }

  function getAllGrades() {
    return $this->all_grades;
  }

  function getGradeName($grade_id) {
    return isset($this->grades_by_id[$grade_id]) ? $this->grades_by_id[$grade_id]->gradeName : null;
  }

  function getGradeId($grade_name) {
    return isset($this->grades_by_name[$grade_name]) ? $this->grades_by_name[$grade_name] : null;
  }


}

}
