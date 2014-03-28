<?php

require_once('RestApi.php');
require_once('LookupTable.php');

/**
 * Description of RestApiLookup
 *
 * @author stevemulligan
 */
class RestApiLookup extends RestApi
{
  public function load_countries_and_states()
  {
    $l = new LookupTable("countries");
    $states = new LookupTable("states");

    $data = $l->getAllRecords();

    $res = array();

    foreach ($data as $rec)
    {
      $state_data = $states->fetchOne('country', $rec['shortName']);
      $state_res = array();
      if (isset($state_data['states'])) {
        foreach ($state_data['states'] as $state_rec)
        {
          $y = array('key' => $state_rec['shortName'], 'value' => $state_rec['longName']);
          array_push($state_res, $y);
        }
      }
      $x = array('key' => $rec['shortName'], 'value' => $rec['longName'], 'states' => $state_res);
      array_push($res, $x);
    }

    return array('status' => 'ok', 'result' => $res);
  }

  public function load_countries()
  {
    $l = new LookupTable("countries");
   
    $data = $l->getAllRecords();

    $res = array();

    foreach ($data as $rec)
    {
      $x = array('key' => $rec['shortName'], 'value' => $rec['longName']);
      array_push($res, $x);
    }

    return array('status' => 'ok', 'result' => $res);
  }

  public function load_age_ranges()
  {
    $l = new LookupTable("ageRanges");

    $data = $l->getAllRecords();

    $res = array();

    foreach ($data as $rec)
    {
      $x = array('key' => $rec['id'], 'value' => $rec['longName']);
      array_push($res, $x);
    }

    return array('status' => 'ok', 'result' => $res);
  }

  public function load_grades()
  {
    $l = new LookupTable("grades");

    $data = $l->getAllRecords();

    $res = array();

    foreach ($data as $rec)
    {
      $x = array('key' => $rec['shortName'], 'value' => $rec['longName']);
      array_push($res, $x);
    }

    return array('status' => 'ok', 'result' => $res);
  }

  public function load_school_types()
  {
    $l = new LookupTable("schoolTypes");

    $data = $l->getAllRecords();

    $res = array();

    foreach ($data as $rec)
    {
      $x = array('key' => $rec['shortName'], 'value' => $rec['longName']);
      array_push($res, $x);
    }

    return array('status' => 'ok', 'result' => $res);
  }

  public function load_roles()
  {
    $l = new LookupTable("roles");

    $data = $l->getAllRecords();

    $res = array();

    foreach ($data as $rec)
    {
      $x = array('key' => $rec['shortName'], 'value' => $rec['longName']);
      array_push($res, $x);
    }

    return array('status' => 'ok', 'result' => $res);
  }

  public function states_by_country($country_code)
  {
    $l = new LookupTable("states");

    $data = $l->fetchOne('country', $country_code);

    $res = array();
    if (isset($data['states'])) {
    foreach ($data['states'] as $rec)
    {
      $x = array('key' => $rec['shortName'], 'value' => $rec['longName']);
      array_push($res, $x);
    }
    }

    return array('status' => 'ok', 'result' => $res);
  }




}
