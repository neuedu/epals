<?php

require_once('classes/RestApi.php');

/**
 * Description of RestApiDocument
 *
 * @author stevemulligan
 */ 
class RestApiDocument extends RestApi
{
    public $_model;
    public $_create_config_file = '';
    public $_edit_config_file = '';
    
    function load_document($id)
    {
        $result = array();
        $object = new $this->_model();
        $object->id = $id;
        
        if ($object->get() === FALSE)
        {
            $result['status'] = 'fail';
            $result['errors'] = array($this->_build_error('Could not find ' . $this->_model . ' with that id', 'id', 'general'));
        } else {
            $result['result'] = $object->toArray();
            $result['status'] = 'ok';
        }
        
        return $result;
    }
    
    function create_document()
    {
        $result = array();
        $object = new $this->_model;
        
        $request_object = $this->_request_object();
        $doc = array();
        if (!is_null($request_object))
        {
          $errors = $this->_create($this->_create_config_file, $request_object, $doc);
        } else {
          $errors = array($this->_build_error('Could not parse request object', '', 'general'));
        }

        if (count($errors) == 0)
        {
          foreach ($doc as $k => $v)
          {
              $x = 'set' . ucfirst($k);
              $object->$x($v); // keanu: whoa
          }
          
          $res = $object->add();
          
          $res['id'] = $res['_id'];
          $result['result'] = $res;
          $result['status'] = 'ok';
        } else {
          $result['status'] = 'fail';
          $result['errors'] = $errors;
        }
        
        return $result;
    
    }
    
    function edit_document($id)
    {
        $result = array();
        
        $object = new $this->_model();
        $object->id = $id;
        
        if ($object->get() === FALSE)
        {
            $result['status'] = 'fail';
            $result['errors'] = array($this->_build_error('Could not find ' . $this->_model . ' with that id', 'id', 'general'));
        } else {
            $request_object = $this->_request_object();
            $doc = array();
            if (!is_null($request_object))
            {
              $errors = $this->_create($this->_edit_config_file, $request_object, $doc);
            } else {
              $errors = array($this->_build_error('Could not parse request object', '', 'general'));
            }

            if (count($errors) == 0)
            {
              foreach ($doc as $k => $v)
              {
                  $x = 'set' . ucfirst($k);
                  if (method_exists($object, $x)) { $object->$x($v); } // keanu: whoa
              }

              $res = $object->update();
              $result['result'] = $res;
              $result['status'] = 'ok';
            } else {
              $result['status'] = 'fail';
              $result['errors'] = $errors;
            }
        }
        
        return $result;
    }
    
    function delete_document($id)
    {
        $result = array();
        $object = new $this->_model();
        $object->id = $id;
        
        $res = $object->delete();
            
        $result['result'] = $res;
        $result['status'] = 'ok';
        
        return $result;
    }
    
    
}

?>
