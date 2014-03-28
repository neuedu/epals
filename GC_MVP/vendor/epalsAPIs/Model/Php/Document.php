<?php

require_once("UUID.php");
require_once("Policy.php");

class Document {
    
    public $createDate;
    public $id;
    public $modifyDate;
    
    protected $memcache;

    
    function __construct() {
        $ini = parse_ini_file('Config.ini',TRUE);
        $tenant = $ini["tenant"]["name"];
        $tenant = str_replace(".", "_", $tenant);
        $this->tenant = $tenant;
        $this->name = strtolower(get_called_class());
        $this->memcache = new Memcache();
        $this->memcache->pconnect($ini["memcache"]["host"], $ini["memcache"]["port"]);
        //$this->policy = new Policy();
        //$this->acl = $this->policy->getAcl();
    }
    
    function add() {
         if (is_null($this->id)) {
            $uuid = new UUID();
            $this->id = $uuid->create();
        }
        $this->createDate = time();
    }
        
    function addElement($array, $k) {
        if (is_null($this->$array)) $this->$array = array();
        if (in_array($k, $this->$array)) {
            return true;
        }
        try {
            array_push($this->$array, $k);
        } catch (Exception $e) {
            error_log("error adding  $k to $this->$array: $e");
            return false;
        }
        return true;
    }

    function is_assoc($array) {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    function deleteSubElement($array, $key, $value) {
      $new_array = array();
      foreach ($this->$array as $assoc)
      {
         if ($assoc[$key] != $value)
         {
           array_push($new_array, $assoc); 
         }
      }
      $this->$array = $new_array;
      return true;
    }
    
    function deleteElement($array, $k) {
        if (!in_array($k, $this->$array)) {
            return true;
        }
        if (!is_array($this->$array))
        {
            return true;
        }
       
        if ($this->is_assoc($this->$array)) {
          foreach ($this->$array as $key => $val) {
            if ($k == $key) {
                try {
                    unset($this->{$array}[$key]);
                } catch (Exception $e) {
                    error_log("error deleting $k from $this->$array: $e");
                    return false;
                }
            }
          }
        } else {
             $new_array = array();
             foreach ($this->$array as $key)
             {
               if ($k != $key) {
                 array_push($new_array, $key);
               }
             }
             $this->$array = $new_array;
        }
        
        return true;
    }
    
    function cleanAtts ($currentAtts) {
        unset($currentAtts["m"]);
        unset($currentAtts["collection"]);
        unset($currentAtts["memcache"]);
        unset($currentAtts["elasticSearchClient"]);
        unset($currentAtts["options"]);
        unset ($currentAtts["solrClient"]);
        return $currentAtts;
    }

    
}
?>
