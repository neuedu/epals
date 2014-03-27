<?php

require_once("MongoDocument.php");
require_once("Query.php");

class LookupTable extends MongoDocument {
    
    public $id;
    public $shortName;
    public $longName;
    public $tableName;
    public $memcache;
    
    function __construct($name) {
        parent::__construct();
        $this->tableName = $name;
        $this->collection = $this->m->selectCollection("gc", $name);
        $this->saveSOLR = FALSE;
        $this->memcache = new Memcache();
        $this->memcache->pconnect(EPALS_MEMCACHE_HOST, EPALS_MEMCACHE_PORT);
    }

    /* findByKey will retrieve the specified key from a lookup table, I used this in the site_config collection for the titles for teachers on the registration page
       It is intended to return a complete object, indexed by key, so if you want to pull an array out of the DB, this is an easy way to do it:
 
       eg:
    
        $l = new LookupTable("site_config");
        $res = $l->findByKey("registration_teacher_titles");

        //$res now contians an array of strings that I use to populate the list box on the join page

    */
    function findByKey($key)
    {
      $i = $this->collection->findOne(array($key => array('$exists' => true)));
      if ($i[$key])
      {
         return $i["registration_teacher_titles"];
      } else {
         throw new Exception('Key not found in lookup table');
      }
    }

    function getHash()
    {
        $data = $this->getAllRecords();

        $res = array();
        foreach ($data as $rec)
        {
          $res[$rec['id']] = $rec['longName'];
        }
        return $res;
    }
    
    function getByShort($short) {
        return $this->fetch("shortName", $short);
    }
    
    function getByID($id) {
        return $this->fetchByID($id);
    }
    
    static function getLongName($name,$id) {
        $memcache = new Memcache();
        $memcache->pconnect(EPALS_MEMCACHE_HOST, EPALS_MEMCACHE_PORT);
        $q = new Query("mongo", $name, "gc");
        $id = strval($id);
        $cacheKey = $name . "_" . $id;
        if (!$long = $memcache->get($cacheKey)) {
           $res = $q->mongoFetch("id", $id);
           if (count($res)) {
               try {
                   $long = $res[0]["longName"];
               } catch (Exception $e) {
                   error_log($e);
                   return null;
               }
           } else {
               return null;
           }
           $memcache->set($cacheKey, $long, null,86400);
        }
        return $long;
    }
}

?>
