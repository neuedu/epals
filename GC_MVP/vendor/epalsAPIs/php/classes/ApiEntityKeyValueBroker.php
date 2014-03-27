<?php

namespace Epals {

require_once('ApiEntityBroker.php');

/**
 * Description of ApiEntityKeyValue
 *
 * @author stevemulligan
 */

class ApiEntityKeyValueBroker extends ApiEntityBroker 
{
    function __construct($session)
    {
       parent::__construct($session);
       $this->onBehalfOf = $session->getUsername();
    }

    function add($keyValueObject) // key values dont really have an 'add' per say, you just update them. add is the same thing for now.
    {
        return $this->update($keyValueObject);
    }

    function update($keyValueObject)
    {
        $request_object = $this->objectToJSON($keyValueObject);
        $x = $this->curl->put($this->update_url($this->onBehalfOf), $request_object)->body;
echo "X: $x\n";
        $response = json_decode($x);

        if ($response->status == 'ok')
        {
          $res = TRUE;
        } else {
          $object->addError($response->errors);
          $res = FALSE;
        }
        return $res;
    }

    function load_url($id, $key = null)
    {
        if (is_null($key))
        {
            $url = $this->hostname . $this->endpoint_load_many . '/' . $id;
        } 
        else 
        {
            $url = $this->hostname . $this->endpoint_load . '/' . $id . '/' . $key;
        }
        $url .= $this->queryString();
        return $url;
    }

    function delete_url($id, $key)
    {
        $url = $this->hostname . $this->endpoint_delete . '/' . $id;
        if (!is_null($key)) $url .= '/' . $key;
        $url .=  $this->queryString();
        return $url;
    }

    function load()
    {
        $response = json_decode($this->curl->get($this->load_url($this->onBehalfOf)));
        if ($response->status == 'ok')
        {
          $res = TRUE;
        } else {
          $object->addError($response->errors);
          $res = FALSE;
        }
        return $res;
    }

    function delete($key)
    { 
      $u = $this->delete_url($this->onBehalfOf, $key);
      $x = $this->curl->delete($u)->body;
      $response = json_decode($x);
      if ($response->status == 'ok')
      {
          $res = TRUE;
      } else {
          $object->addError($response->errors);
          $res = FALSE;
      }
      return $res;
    }
   
}
}
