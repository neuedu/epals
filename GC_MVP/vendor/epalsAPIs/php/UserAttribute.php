<?php

require_once("classes/Record.php");
require_once("user.php");

class UserAttribute extends Record {
    
    public $id;
    protected $attributes;
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
            throw new Exception("Key can't be null");
        }
        $tmp = array($key => $value);
        if (is_null($this->attributes)) {
            $this->attributes = $tmp;
            $res = parent::add();
        } else {
            $this->attributes = array_merge($this->attributes,$tmp);
            $res = parent::update();
        }
        return $res;
    }
    
    function getAll() {
        return $this->attributes;
    }
    
    function get($key) {
        if ((empty($key)) || is_null($key)) {
            throw new Exception("Key can't be run");
        }
        
        if (!array_key_exists($key, $this->attributes))
            throw new Exception ("Attribute $key does not exist!");
        
        return $this->attributes[$key];
    }
}

?>
