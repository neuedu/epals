<?php

namespace Epals {

require_once('EpalsLookupTable.php');

class Country {
  public $countryName;
  public $countryCode;
  public $countryProvinces;
}

class Province {
  public $provinceName;
  public $provinceCode;
}

class CountryLookup extends LookupTable {

  protected $endpoint_load = '/lookup/countries-and-states';
  protected $all_countries;
  protected $countries_by_code;
  protected $countries_by_name;
  protected $states_for_country;

  function load()
  {
    parent::load();
    $countries = array();
    foreach ($this->data as $c)
    {
      $co = new Country();
      $co->countryName = $c->value;
      $co->countryCode = $c->key;

      $provinces = array();
      foreach ($c->states as $s)
      {
        $po = new Province();
        $po->provinceName = $s->value;
        $po->provinceCode = $s->key;
        array_push($provinces, $po);
      }
      $co->provinces = $provinces;
      array_push($countries, $co); 
      $this->countries_by_code[$c->key] = $co;
      $this->countries_by_name[$c->value] = $c->key; 
      $this->states_for_country[$c->key] = $c->states; 
    } 
    $this->all_countries = $countries;
  }

  function getAllCountries() {
    return $this->all_countries;
  }

  function getCountryName($country_code) {
    return isset($this->countries_by_code[$country_code]) ? $this->countries_by_code[$country_code]->countryName : null;
  }

  function getCountryCode($country_name) {
    return isset($this->countries_by_name[$country_name]) ? $this->countries_by_name[$country_name] : null;
  }

  function getCountryProvinces($country_code) {
    return isset($this->countries_by_code[$country_code]) ? $this->countries_by_code[$country_code]->provinces : null;
  }


}

}
