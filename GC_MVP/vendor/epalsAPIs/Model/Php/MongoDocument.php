<?php

require_once("Document.php");

class MongoDocument extends Document {
    
    public $m;
    public $collection;
    public $tenant;
    
    private $_mongoID;
    
    function __construct() {
         parent::__construct();
        $ini = parse_ini_file('Config.ini',TRUE);
        $this->m = new Mongo($ini["mongo"]["connection"]);
    }
    
    
     /* Add expects that you've instantiated a new object and set the properties
        Eg:
            $mycategory = new category();
            $mycategory->name = "Mystics";
            $mycategory->parent = "290";
            $mycategory->id = "299
            $mycategory->add();
    */
     
    function add() {
        parent::add();
        $currentAtts = get_object_vars($this);
        $currentAtts = $this->cleanAtts($currentAtts);
        $res = $this->collection->save($currentAtts, array("fsync"=>true));
        $this->memcache->set($this->id, $currentAtts, false, 300);
        return $res;
    }
    
    /*
      Update expects that you've already set the attributes you'd like to update:
      $c = new Person();
      $c->fetchOne("name","kermit frog");
      $c->name = "kermit the frog";
      $c->update();
      
      This will update the name of the person to "kermit the frog" and save that state
      in the database. 
    */
    
    public function update() {
        $attArray = get_object_vars($this);

        $attsToSave = array_diff_key($attArray, get_class_vars("Document"));

        unset($attsToSave["_id"]);
        $res = $this->collection->update(array("id" => $this->id), array('$set'=>$attsToSave), array("safe"=>true));
        $this->memcache->set($this->id, $attsToSave, false, 300);
        return $res;
    }
    
    public function fetchByID($id) {
        $result = $this->memcache->get($id);
        $result = false;
        if ($result != true) {
            $result = $this->fetchOne("id", $id);
        } else {
            $keys = array_keys($result);
            if ($keys) {
                foreach ($keys as $key) {
                    $this->$key = $result[$key];
                }
            }
        }
        return $result;
    }
    
    public function fetchOne($queryKey, $queryValue) {
       
        $result = $this->collection->findOne(array($queryKey => $queryValue));
        $this->_mongoID = $result['_id'];
        if ($result) {
            $keys = array_keys($result);
            if ($keys) {
                foreach ($keys as $key) {
                    $this->$key = $result[$key];
                }
            }
        }
        return $result;                                                                                                                                                                                                                                                                                                                                                                 
    }
    
    function fetch($queryKey, $queryValue, $sortKey = "createDate", $skip = 0, $limit = null) {
        $res = array();
        $cursor = $this->collection->find(array($queryKey => $queryValue))->limit($limit)->skip($skip); 
        //$cursor = $this->collection->sort(array("createDate" => 1));
        foreach ($cursor as $doc) {
            array_push($res, $doc);
        }
        return $res;
    }
    
    function mongoQueryIn($key, $value) {
        $res = array();
        $cursor = $this->collection->find(array($key => array('$in' => array($value))));
        foreach ($cursor as $doc) {
            array_push($res, $doc);
        }
        return $res;
    }
    
    function delete($id) {
        $res = $this->collection->remove(array('_id' => new MongoId($this->_id)));
        $mcres = $this->memcache->delete($id);
        return $res;
        
    }

    function deleteByQuery($queryKey, $queryValue) {
        $res = $this->collection->remove(array($queryKey => $queryValue), array("safe" => true));
        return $res;
    }
    
    public function getAllRecords() {
        $res = array();
        $cursor = $this->collection->find();
        $cursor->sort(array("createDate" => 1));
        foreach ($cursor as $doc) {
            array_push($res, $doc);
        }
        return $res;
    }
    
    public function getRecordSet($skip, $limit) {
        $res = array();
        $cursor = $this->collection->find()->limit($limit)->skip($skip);
        $cursor->sort(array("createDate" => 1));
        $count = $cursor->count();
        foreach ($cursor as $doc) {
            array_push($res, $doc);
        }
        $res2 = array($count, $res);
        //var_dump($res2);
        return $res2;
    }
    
    function dropAllRecords() {
        $result = $this->collection->drop();
        return $result;
    }
    
    function count() {
        $count = $this->collection->count();
        if (is_null($count)) {
            return 0;
        }
        return $count;
    }
}

