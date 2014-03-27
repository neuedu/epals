<?php


namespace Epals {
require_once('ApiEntity.php');

/**
 * Description of ApiEntityDocument
 *
 * @author stevemulligan
 */

class ApiEntityDocument extends ApiEntity 
{
    function __construct($session = null, $id = null)
    { 
        parent::__construct($session);
        if (!is_null($id))
        {
            $obj = $this->load($id);
            
            if ($obj->status == "ok")
            {
                $this->data = $this->objectToArray($obj->result);
            } else {
                $this->data = array();
                $this->errors = $obj->errors;
            }
        }
    }
    
    function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(array($this, 'objectToArray'), $d);
		}
		else {
			// Return array
			return $d;
		}
	}

    
    function update()
    {
      $request_object = json_encode($this->data);
      $response = json_decode($this->curl->put($this->update_url($this->getId()), $request_object)->body);
      return $response;
    }

    function delete()
    {
      $response = json_decode($this->curl->delete($this->delete_url($this->getId()))->body);
      return $response;
    }

}
}
