<?php

require_once('classes/RestApi.php');
require_once('record/ProfilePublic.php');
require_once('record/ProfilePending.php');

class RestApiProfile extends RestApi
{

  function _load_by_id($id)
  {
    $p = new TwoPhase\ProfilePublic($id);
    $p->approved = true;
    if (is_null($p->id))
    {
      $p = new TwoPhase\ProfilePending($id);
      $p->approved = false;
    }
    return $p;
  }

 function _convert_to_response_object(&$mongo_profile)
 {
   unset($mongo_profile['_id']);
   unset($mongo_profile['moderation_log']);
 }

 function profiles_by_account($account_id)
 {
   $result = array();
   $request_object = array();
   $this->prepareSession($request_object);
   if (!isset($this->website_session))
   {
      $errors = array($this->_build_error('A valid session id is required to load all profiles that belong to a user', 'session_id'));
      $result['status'] = 'fail';
      $result['errors'] = $errors;
   } 
   else 
   {
      $user_id = $this->user_id_from_username($account_id);
      if ($user_id > 0)
      {
        $result['status'] = 'ok';
        $p = new TwoPhase\ProfilePublic();
        $data = array();

        $res = $p->findByMongo(array('user_id' => $user_id));
        foreach ($res as $r)
        {
          $r['approved'] = true;
          $this->_convert_to_response_object($r);
          array_push($data, $r);
        }

        $p = new TwoPhase\ProfilePending();
        $res = $p->findByMongo(array('user_id' => $user_id));
        foreach ($res as $r)
        {
          if ($this->isOwnerOrAdmin($r))
          {
            $r['approved'] = false;
            $this->_convert_to_response_object($r);
            array_push($data, $r);
          }
        }
        $result['result'] = $data;
      } else {
        $result['status'] = 'fail';
        $result['errors'] = array($this->_build_error('Could not find that username', 'account_id', 'general'));
      }
   }

   return $result;

 }

 function profile_by_id($id)
 {
   $result = array();
   if ($p = $this->_pre_process_load_request($id, '', &$result))
   {
     $errors = array();
     if (get_class($p) == 'TwoPhase\\ProfilePending')
     {
       if (isset($this->website_session))
       {
          if (!$this->isOwnerOrAdmin($p))
          {
            $errors = array($this->_build_error('Can not load a pending profile unless you are the owner or an admin', 'session_id', 'general'));
          }
       } else {
          $errors = array($this->_build_error('Can not load a pending profile without a session id', 'session_id', 'general'));
       }
     }

     if (count($errors) == 0)
     {
       $result['status'] = 'ok';
       $data = $p->get_data_copy();
       $data['class'] = get_class($p);
       $result['result'] = $data;
     } else {
       $result['status'] = 'fail';
       $result['errors'] = $errors;
     }
   }

   return $result;
 }

 function verify_session()
 {
     $errors = array();

     if (!isset($this->website_session))
     {
       $errors = array($this->_build_error('session_id is required in the request object', 'session_id', 'general'));
     }
   
     return $errors;
 }

 function profile_create()
 {
    $result = array();
    $p = new TwoPhase\ProfilePending();

    $errors = array();
    $request_object = $this->_request_object($errors);
    if (count($errors) == 0)
    {
      $errors = $this->verify_session();
    }

    if (count($errors) == 0)
    {
      if (!is_null($request_object))
      {
        $errors = $this->_create('api_profile_create.json', $request_object, $p);
      } else {
        $errors = array($this->_build_error('Could not parse request object', '', 'general'));
      }
    }

    if (count($errors) == 0)
    {
      $p->app_id = $this->app_id;
      $p->status = 5;
      if (isset($p->draft)) $p->status = 6;
      $p->insert();
      $result['result'] = array('id' => $p->id);
      $result['status'] = 'ok';
    } else {
      $result['status'] = 'fail';
      $result['errors'] = $errors;
    }

    return $result;
 }

 function isOwnerOrAdmin($profile)
 {
   if (is_array($profile))
   {
     if (isset($profile['user_id']))
     {
      $canEdit = (($this->website_session->getUserId() > 0) && ($profile['user_id'] == $this->website_session->getUserId())); // owner
     }
     if (isset($profile['app_id'])) { 
      if ($this->website_session->isAdmin() && ($profile['app_id'] == $this->app_id)) { $canEdit = true; } // admin
     }
   } else {
     $canEdit = (($this->website_session->getUserId() > 0) && ($profile->user_id == $this->website_session->getUserId())); // owner
     if ($this->website_session->isAdmin() && ($profile->app_id == $this->app_id)) { $canEdit = true; } // admin
   }

   return $canEdit;
 }

 function profile_delete($id)
 {
   $result = array();
   $request_object = null;
   if ($p = $this->_pre_process_load_request($id, '', $result, $request_object))
   {
     $errors = $this->verify_session();
     if (count($errors) == 0)
     {
       if ($this->isOwnerOrAdmin($p)) 
       {
         $p->delete();
         $result['status'] = 'ok';
       } else {
         $errors = array($this->_build_error('You do not have permission to delete that profile', '', 'general'));
       }
     }

     if (count($errors) > 0)
     {
         $result['status'] = 'fail';
         $result['errors'] = $errors;
     }
   }

   return $result;
 }

 function profile_edit($id)
 {
    $result = array();
    if ($p = $this->_pre_process_load_request($id, 'api_profile_edit.json', $result))
    {
        $errors = $this->verify_session();
        if (count($errors) == 0)
        {
          if ($this->isOwnerOrAdmin($p))
          {
            $np = new TwoPhase\ProfilePending(); 
            $data = $p->get_data_copy();
            if (get_class($p) == 'TwoPhase\\ProfilePublic')
            {
              unset($data['id']);
              $np->set_data($data);
              $np->approved_profile_id = $p->id;
              $np->insert();
            } else {
              $np->set_data($data);
              $np->update();
            }
            $result['result'] = array('id' => $np->id);
            $result['status'] = 'ok';
          } else {
            $errors = array($this->_build_error('You do not have permission to edit that profile', '', 'general'));
          }
        }
        if (count($errors) > 0)
        {
            $result['status'] = 'fail';
            $result['errors'] = $errors;
        }
    }

    return $result;
 }
 
 function _create($config_file, $request_object, &$response_object) {
     if ($config_file != "")
        return parent::_create($config_file, $request_object, $response_object);
     else
        return array();
 }

 function _pre_process_load_request($id, $endpoint_config_file, &$result, &$request_object = null)
 {
    $p = $this->_load_by_id($id);
    if ($p->id != '')
    {
      $request_object = $this->_request_object();
      
      if ($request_object !== FALSE)
      {
        $errors = $this->_create($endpoint_config_file, $request_object, $p);
      } else {
        $errors = array($this->_build_error('Could not parse request object, using http://jsonlint.com/ to make sure the object is valid.', '', 'general'));
      }
      
      if (count($errors) > 0)
      {
        $p = null;
        $result['status'] = 'fail';
        $result['errors'] = $errors;
      }
    } else {
      $p = null;
      $result['status'] = 'fail';
      $result['errors'] = array($this->_build_error('Could not find a profile for the specified id: ' . $id, 'id'));
    }
    
    return $p;
 }

 //This will not send a profile back to the moderation queue, the edit happens right away even for approved profiles
 function profile_admin_edit($id)
 {
    $result = array();
    $errors = array();
    $request_object = null;
    if ($p = $this->_pre_process_load_request($id, 'api_profile_admin_edit.json', $result))
    {
        if (!isset($this->website_session))
        {
          $errors = array($this->_build_error('A valid session id is required to make admin edits to a profile', 'session_id'));
        } else {
          if (!$this->website_session->isAdmin())
          {
            $errors = array($this->_build_error('The user that owns the current session is not an admin', 'session_id'));
          }
        }

        if (count($errors) == 0)
        {
          $p->update();
          $result['id'] = $p->id;
          $result['status'] = 'ok';
        } else {
          $result['status'] = 'fail';
          $result['errors'] = $errors;
        }
    }

    return $result;
  }

  function profile_approve($id)
  {
    $result = array(); 
    $errors = array();
    if ($p = $this->_pre_process_load_request($id, '', $result))
    {
        if (!isset($this->website_session))
        {
          $errors = array($this->_build_error('A valid session id is required to approve a profile', 'session_id'));
        } else {
          if (!$this->website_session->isAdmin())
          {
            $errors = array($this->_build_error('The user that owns the current session is not an admin', 'session_id'));
          }
        }

        if (count($errors) == 0)
        {
          $p->approve();
          $result['result'] = array('id' => $p->transformed['id']);
          $result['status'] = 'ok';
        } else {
          $result['status'] = 'fail';
          $result['errors'] = $errors;
        }
    }

    return $result;
  }

  function profile_hold($id)
  {
    $result = array();
    $request_object = null;
    $errors = array();
    if ($p = $this->_pre_process_load_request($id, 'api_profile_hold.json', $result, $request_object))
    {
        if (!isset($this->website_session))
        {
          $errors = array($this->_build_error('A valid session id is required to place a profile on hold', 'session_id'));
        } else {
          if (!$this->website_session->isAdmin())
          {
            $errors = array($this->_build_error('The user that owns the current session is not an admin', 'session_id'));
          }
        }

        if (count($errors) == 0)
        {
          $p->hold($request_object['holding_comments']);
          $result['result'] = array('id' => $p->transformed['id']);
          $result['status'] = 'ok';
        } else {
          $result['status'] = 'fail';
          $result['errors'] = $errors;
        }
    }
    return $result;
  }

  function profiles_pending($limit = 10, $offset = 0)
  {
    $result = array();

    $errors = array();

    if (!isset($this->website_session))
    {
      $errors = array($this->_build_error('A valid session id is required to see all pending profiles', 'session_id'));
    } else {
      if (!$this->website_session->isAdmin())
      {
        $errors = array($this->_build_error('The user that owns the current session is not an admin', 'session_id'));
      }
    }

    if (count($errors) == 0)
    {
      $p = new TwoPhase\ProfilePending();
      $query['status'] = array('$in' => array(PROFILE_STATUS_PENDING, PROFILE_STATUS_AUTO_APPROVED));
      $mres = $p->findByMongo($query, $limit, $offset, array('last_modified' => -1), array('id'));
      $ids = array();
      foreach ($mres as $r)
      {
        array_push($ids, $r['id']);
      }
      $result['status'] = 'ok';
      $result['result'] = $ids;
    } else {
      $result['status'] = 'fail';
      $result['errors'] = $errors;
    }

    return $result;
  }

}


