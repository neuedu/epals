<?php

namespace Epals {

require_once('EpalsLookupTable.php');

class AgeRange {
  public $ageId;
  public $ageRange;
}

class AgeRangeLookup extends LookupTable {

  protected $endpoint_load = '/lookup/age-ranges';
  protected $all_age_ranges;
  protected $age_ranges_by_code;
  protected $age_ranges_by_name;

  function load()
  {
    parent::load();
    $age_ranges = array();
    foreach ($this->data as $c)
    {
      $co = new AgeRange();
      $co->ageRange = $c->value;
      $co->ageId = $c->key;

      array_push($age_ranges, $co); 
      $this->age_ranges_by_id[$c->key] = $co;
      $this->age_ranges_by_name[$c->value] = $c->key; 
    } 
    $this->all_age_ranges = $age_ranges;
  }

  function getAllAgeRanges() {
    return $this->all_age_ranges;
  }

  function getAgeRangeId($age_range) {
    return isset($this->age_ranges_by_name[$age_range]) ? $this->age_ranges_by_name[$age_range] : null;
  }

  function getAgeRangeName($age_range_id) {
    return isset($this->age_ranges_by_id[$age_range_id]) ? $this->age_ranges_by_id[$age_range_id]->ageRange : null;
  }


}

}
