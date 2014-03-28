<?php



require_once("UUID.php");
require_once("Record.php");

class UserData extends Record {
    public $id;
    private $mode;


    function __construct($user,$data) {
        parent::__construct();
        $this->id = $user;
        $res = parent::get();
        if (is_null($this->$data)) {
            $this->mode = "add";
        } else {
            $this->mode = "update";
        }
    }
    
    public function add($key,$value) {
        $tmp = array($key => $value);
        switch ($this->mode) {
            case "add": 
                $this->$data = $tmp;
                $res = parent::add();
                break;
            case "update":
                $this->$data = array_merge($this->attributes,$tmp);
                $res = parent::update();
                break;
        }
        return $res;
    }
    
    function getAll() {
        return $this->$data;
    }
    
    function get($key) {
        return $this->$data[$key];
    }
}

?>
