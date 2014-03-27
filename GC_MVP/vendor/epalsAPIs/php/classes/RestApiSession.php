<?php

require_once('EpalsLogin.php');
require_once('RestApi.php');

/**
 * Description of RestApiAttributeInterface
 *
 * @author stevemulligan
 */
class RestApiSession extends RestApi
{
  public $_create_config_file = 'api_session_create.json';

  function _convert_to_response_object($session)
  {
    $r = array();
    $r['role'] = $session->getRole();
    $r['user_id'] = $session->getUserId();
    $r['session_id'] = $session->getSessionId();
    if (!is_null($session->getSessionData())) { $r['data'] = $session->getSessionData(); }
    return $r;
  }

  function create_session()
  {
    $result = array();
    $e = new EpalsLogin();
    $request_object = $this->_request_object();
    if (!is_null($request_object))
    {
      $errors = $this->_create($this->_create_config_file, $request_object, $doc);
    } else {
      $errors = array($this->_build_error('Could not parse request object', '', 'general'));
    }
    if (count($errors) == 0)
    {
      $session = $e->login($request_object['username'], $request_object['password']);
      if (!(is_null($session)))
      {
        $result['result'] = $this->_convert_to_response_object($session);
        $result['status'] = 'ok';
      } else {
        $result['status'] = 'fail';
        $result['errors'] = array($this->_build_error('Invalid username or password', '', 'general'));
      }
    } 
    else
    {
      $result['status'] = 'fail';
      $result['errors'] = $errors;
    }
    return $result;
  }

  function delete_session($id)
  {
    $result = array();
    $e = new EpalsLogin($id);

    $e->logout();

    $result['result'] = 'ok';
    $result['status'] = 'ok';

    return $result;
  }    
}
