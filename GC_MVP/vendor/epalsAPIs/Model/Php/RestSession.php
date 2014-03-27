<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Event
 *
 * @author root
 */

require_once("Record.php");

class RestSession extends Record {
    
    public $user_id;
    public $role;
    public $sessionData;
    public $expired = FALSE;

    public $SESSION_DURATION_MINUTES = 90; 

    function __construct($id = null)
    {
        parent::__construct();
        if (is_null($id))
        {
           $this->add();  // this is odd, but teh intent is that no session id means you MUST create a new one if you are using this.
                          // when you load an old session, you need to call update() on it at least once per action(page view etc)
        } else {
           $this->id = $id;
           if ($this->get() === FALSE) {
             $this->id = ''; // not good, only indication teh session is not found, exceptions are less friendly imo though
           } 
	}
    }

    public function getSessionId()
    {
      return $this->id;
    }

    public function hasSessionExpired()
    {
      if (time() > ($this->modifyDate + ($this->SESSION_DURATION_MINUTES * 60)))
      {
         $this->expireSession();
      }
      return $this->expired;
    }

    public function expireSession()
    {
        $this->expired = TRUE;
        $this->update();
    }
   
    public function getUserId()
    {
        return $this->user_id;
    }

    public function isAdmin()
    {
        return (preg_match('/admin/i', $this->role) === 1);
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setSessionData($data) {
        $this->sessionData = $data;
    }

    public function getSessionData() {
        return $this->sessionData;
    }
}
