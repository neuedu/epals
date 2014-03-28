<?php
namespace Epals {

require_once('ApiEntityObject.php');

/**
 * Description of Event
 *
 * @author stevemulligan
 */

class Session extends ApiEntityObject {

    function getRole()
    {
       return isset($this->data['role']) ? $this->data['role'] : null;
    }

    function setRole($role)
    {
       $this->data['role'] = $role;
    }

    function getUsername() {
       return isset($this->data['username']) ? $this->data['username'] : null;
    }

    function setUsername($username) {
       $this->data['username'] = $username;
    }

    function setUserId($user_id)
    {
       $this->data['user_id'] = $user_id;
    }

    function getUserId()
    {
       return isset($this->data['user_id']) ? $this->data['user_id'] : null;
    }
    
    function getData()
    {
       return isset($this->data['data']) ? $this->data['data'] : null;
    }

    function setData($data)
    {
       $this->data['data'] = $data;
    }

    function isAuthenticated()
    {
       if (isset($this->data['user_id']))
       {
         $res = (intval($this->data['user_id']) > 0);
       } else {
         $res = FALSE;
       }
       return $res; 
    }
}
}
