<?php

require_once('classes/RestApi.php');

/**
 * Description of RestApiAttributeInterface
 *
 * @author stevemulligan
 */
class RestApiKeyValue extends RestApi 
{
    public $_model;
    public $_request_config = '';
    
    function load_value($account, $key)
    {
        $result = array();
        $object = new $this->_model($account);
        $one = $object->get($key);
        $result['result'] = (is_null($one) ? array() : $one);
        $result['status'] = 'ok';
        return $result;
    }
    
    function load_values($account)
    {
        $result = array();
        $object = new $this->_model($account);
        $all = $object->getAll();
        $result['result'] = (is_null($all) ? array() : $all);
        $result['status'] = 'ok';
        return $result;
    }
    
    function delete_value($account, $key)
    {
        $result = array();
        
        $object = new $this->_model($account);
            
        $res = $object->add($key, null);
            
        $result['status'] = 'ok';
        
        return $result;
    }
    
    function set_values($account)
    {
        $result = array();
        
        $request_object = $this->_request_object();

        $data = array();
        if (!is_null($request_object))
        {
          $errors = $this->_create($this->_request_config, $request_object, $data);
        } 
        else 
        {
          $errors = array($this->_build_error('Could not parse request object', '', 'general'));
        }

        if (count($errors) == 0)
        {
            $object = new $this->_model($account);
            
            foreach ($data as $k => $v)
            {
                $res = $object->add($k, $v);
            }
            
            $result['status'] = 'ok';
        } 
        else 
        {
          $result['status'] = 'fail';
          $result['errors'] = $errors;
        }
        
        return $result;
    }
}

?>
