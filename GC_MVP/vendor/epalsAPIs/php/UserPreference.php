<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserPreference
 *
 * @author shaz
 */

require_once("classes/Record.php");
require_once("user.php");

class UserPreference extends Record {
    public $id;
    protected $preferences;
    private $mode;


    function __construct($user) {      
        $u = new User();
        $u->loadUser($user);
        if($u->getAccount()) {
            parent::__construct();
            $this->id = $user;
            $res = parent::get();
        }
        else {
            throw new Exception("User $user does not exist!");
        }
    }
   
    
    public function add($key,$value) {
        if ((empty($key)) || is_null($key)) {
            throw new Exception("Key can't be run");
        }
        $tmp = array($key => $value);
         if (is_null($this->preferences)) {
            $this->preferences = $tmp;
            $res = parent::add();
        } else {
            $this->preferences = array_merge($this->preferences,$tmp);
            $res = parent::update();
        }
        return $res;
    }
    
    function getAll() {
        return $this->preferences;
    }
    
    function get($key) {
        if ((empty($key)) || is_null($key)) {
            throw new Exception("Key can't be run");
        }
        return $this->preferences[$key];
    }
}

?>
