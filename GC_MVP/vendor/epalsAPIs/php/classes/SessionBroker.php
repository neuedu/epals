<?php

namespace Epals {
require_once('ApiEntityBroker.php');

class SessionBroker extends ApiEntityBroker {

   protected $endpoint_create = '/session';
   protected $endpoint_load = '/session';
   protected $endpoint_delete = '/session';
   protected $endpoint_update = '/session';

   function objectToJSON($object)
   {
     return json_encode($object);
   }

   function add($login_object) {
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

   function login($username, $password)
   {
       $s = new Session();
       $login_object = array("username" => $username, "password" => $password);
       $response = json_decode($this->curl->post($this->add_url(), json_encode($login_object))->body);
       
       if ($response->status == 'ok') {
         $s->setUsername($username);
         $s->setRole($response->result->role);
         $s->setId($response->result->session_id);
         $s->setUserId($response->result->user_id);
         if (isset($response->result->data)) { $s->setData($response->result->data); }
       } else {
         $s->addError('Username or password is incorrect');
       }

       return $s;
   }

   function logout($session)
   {
       return $this->delete($session);
   }



}

}
