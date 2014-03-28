<?php

namespace Epals {
require_once('curl.php');

class ApiEntityBroker {

    protected $hostname;
    protected $curl;
    protected $onBehalfOf = null;

    public function __construct($session = null)
    {
        $this->curl = new Curl();
        $this->setSession($session);
        $ini = parse_ini_file('api_config.ini', TRUE);
        $this->hostname = $ini['api']['hostname'];
        $this->app_id = $ini['api']['app_id'];
        $this->app_key = $ini['api']['app_key'];
    }

    function onBehalfOf($username)
    {
      $this->onBehalfOf = $username;
    }

    function queryString()
    {
        return '?app_id=' . $this->app_id . '&app_key=' . $this->app_key;
    }
    
/*    function getId()
    {
        return isset($this->data['id']) ? $this->data['id'] : null;
    }
    
    function setId($id)
    {
        $this->data['id'] = $id;
    }*/
    
    function getSession()
    {
        return isset($this->data['session']) ? $this->data['session'] : null;
    }

    function setSession($session)
    {
        if (!is_null($session)) {
          $this->curl->headers = array('ePals-Session-Id' => $session->getId());
        } else {
          $this->curl->headers = array();
        }
    }
    
    protected function add_url() {
        return $this->hostname . $this->endpoint_create . $this->queryString();
    }

    protected function load_url($id) {
        return $this->hostname . $this->endpoint_load . '/' . $id . $this->queryString();
    }

    protected function update_url($id) {
        return $this->hostname . $this->endpoint_update . '/' . $id . $this->queryString();
    }

    protected function delete_url($id) {
        return $this->hostname . $this->endpoint_delete . '/' . $id . $this->queryString();
    }

    function objectToJSON($object)
    {
        return $object->toJSON();
    }

    function add($object) {
        $request_object = $this->objectToJSON($object);
        $response = json_decode($this->curl->post($this->add_url(), $request_object)->body);
        if ($response->status == 'ok')
        {
          if (isset($response->result->id)) { $object->setId($response->result->id); }
          $res = TRUE;
        } else {
          $object->addError($response->errors);
          $res = FALSE;
        }
        return $res;
    }
 
    function createBlankEntity() {
     return new ApiEntityObject(); // should be abstract, override should be required
    }

    function load($object_id) {
        $response = json_decode($this->curl->get($this->load_url($object_id))->body);
        $res = $this->createBlankEntity();
        if ($response->status == 'ok')
        {
          $res->setPrivateData((array)$response->result);
        } else {
          $res->addError($response->error); 
        }
        return $res;
    }

    function update($object) {
        $request_object = $this->objectToJSON($object);
        $x = $this->curl->put($this->update_url($object->getId()), $request_object)->body;
        $response = json_decode($x);
        if ($response->status == 'ok')
        {
          if (isset($response->result->id)) { $object->setId($response->result->id); }
          $res = TRUE;
        } else {
          $object->addError($response->errors);
          $res = FALSE;
        }
        return $res;
    }

    function delete($object) {
        $x = $this->curl->delete($this->delete_url($object->getId()))->body;
        $response = json_decode($x);
        if ($response->status == 'ok')
        {
          if (isset($response->result->id)) { $object->setId($response->result->id); }
          $res = TRUE;
        } else {
          $object->addError($response->errors);
          $res = FALSE;
        }
        return $res;
    }

}
}
