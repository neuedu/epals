<?php

require_once("Document.php");

class SolrDocument extends Document {
    
  
    private $solrClient;
    
    private $_mongoID;
    
    function __construct() {
        parent::__construct();
        $ini = parse_ini_file('Config.ini',TRUE);
        $options = array('hostname' =>$in["solr"]["host"], 'port' => $ini["solr"]["port"]);
        $this->solrClient = new SolrClient($options);
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
        if (is_null($this->id)) {
            $uuid = new UUID();
            $this->id = $uuid->create();
        }
       
        $this->createDate = time();
        $currentAtts = get_object_vars($this);
        $currentAtts = $this->cleanAtts($currentAtts);
       // var_dump($this);
        $this->memcache->set($this->id, $currentAtts, false, 900);
        $doc = new SolrInputDocument();
        $doc->addField('class', get_called_class());
        foreach ($currentAtts as $key => $value) {
            //print("attempting parse and key is ". $key . " and value is " . $value . " \n");
            if (is_array($value)) {
                //print "my array was key";

                foreach ($value as $k=>$v) {
                    //print "my key is " . $k . " and my value is " . $v . "\n";
                    $doc->addField($key,$v);
                }
            } else {
                 $doc->addField($key, $value);
            }
        }
        try {
            $updateResponse = $this->solrClient->addDocument($doc);
            $this->solrClient->commit();
            $this->solrClient->optimize();
        } catch (Exception $e) {
            $this->solrClient->rollback();
        }
    }
    
    
    public function update() {
        $attArray = get_object_vars($this);

        $attsToSave = array_diff_key($attArray, get_class_vars("Document"));

        unset($attsToSave["_id"]);

        $doc = new SolrInputDocument();
        $doc->addField('class', get_called_class());
        $doc->addField('id', $this->id);
        foreach ($attsToSave as $key => $value) {
            if (($key == "") || ($value == "")) {
                //error_log("EMTPYFIELD : $key  => $value");
            } else {
                if (is_array($value))
                {
                   foreach ($value as $k=>$v)
                   {
                     $doc->addField($key, $v);
                   }
                } else {
                   $doc->addField($key, $value);
                }
            }
        }
        try {
            $updateResponse = $this->solrClient->addDocument($doc);
            $this->solrClient->commit();
            $this->solrClient->optimize();
        } catch (Exception $e) {
            error_log("ERR: " . $e->getMessage());
            $this->solrClient->rollback();
        }
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

    function deleteSubElement($array, $key, $value)
    {
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
       
        if ($this->is_assoc($this->$array))
        {
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

    function saveToSOLR() {
        $currentAtts = get_object_vars($this);
        unset($currentAtts["m"]);
        unset($currentAtts["collection"]);
        unset($currentAtts["_mongoID"]);
        unset($currentAtts["memcache"]);
        unset($currentAtts["options"]);
        unset ($currentAtts["solrClient"]);
       // var_dump($this);
        //solr stuff
        if ($this->saveSOLR) {
            $doc = new SolrInputDocument();
            $doc->addField('class', get_called_class());
            foreach ($currentAtts as $key => $value) {
                //print("attempting parse and key is ". $key . " and value is " . $value . " \n");
                if (is_array($value)) {
                    //print "my array was key";

                    foreach ($value as $k=>$v) {
                        //print "my key is " . $k . " and my value is " . $v . "\n";
                        $doc->addField($key,$v);
                    }
                } else {
                     $doc->addField($key, $value);
                }
            }
            try {
                $updateResponse = $this->solrClient->addDocument($doc);
                $this->solrClient->commit();
                $this->solrClient->optimize();
            } catch (Exception $e) {
                $this->solrClient->rollback();
                print("Couldn't save: ". $e);
            }
        }
        return $res;
    }
   
    function delete() {
        $this->solrClient->deleteById($this->id);
        $this->solrClient->commit();
        $this->solrClient->optimize();
    }
}
?>
