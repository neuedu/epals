<?php

namespace TwoPhase
{
    
require_once("UUID.php");
require_once("ConfigStorage.php");

class Record 
{
    // all record data goes in here
    protected $data = array();

    // shouldn't really need to access these, the class will set the collection object
    private $mongo;
    public $mongodb;
    private $ns_key;
    protected $connection_string;

    // avoid accessing directly, but some commands just are not implemented, or will take a lot of effort so
    // these are exposed for now.
    public $memcache;
    public $solrClient;
    public $collection;
    public $transformed;
    public $solrResponse;
    // there is a virtual function that must exist in every subclass of Record.
    //   get_collection_name() must return a string representing the name of this collection (primarily for mongo)

    function __construct($id = null) 
    {
        $this->init_mongo();
        $this->init_memcache();
        $this->init_solr();
        if (!is_null($id))
        {
            $this->findById($id);
        }
    }

    // pass an associative array and it will copy it into the properties of this record
    public function set_data($data)
    {
        $this->data = $data;
    }

    public function set_data_copy($data)
    {
        foreach ($data as $k => $v)
        {
            if ($k == '_id') continue;
            $this->data[$k] = $v;
        }
    }

    public function get_data()
    {
        return $this->data;
    }

    public function get_data_copy()
    {
        $a = array();
        foreach ($this->data as $k => $v)
        {
            $a[$k] = unserialize(serialize($v));
        }
        return $a;
    }

    // __set and __get are attribute overloads,  $foo->some_attribute goes into $this->data['some_attribute']  
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) 
        {
            return $this->data[$name];
        }
        return null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    protected function init_mongo()
    {
        if (isset($this->connection_string))
        {
            $this->mongo = new \Mongo($this->connection_string);
        } 
        else 
        {
            $this->mongo = new \Mongo(EPALS_MONGO_CONNECTION_STRING);
        }
        $this->mongodb = $this->mongo->selectDB(EPALS_MONGO_DATABASE);
        $this->collection = $this->mongodb->selectCollection($this->get_collection_name());
    }

    protected function get_collection_name()
    {
        return get_class();
    }
  
    protected function init_memcache()
    {
        $this->memcache = new \Memcache();
        $this->memcache->pconnect(EPALS_MEMCACHE_HOST, EPALS_MEMCACHE_PORT);
    }

    protected function init_solr()
    {
        $options = array('hostname' => EPALS_SOLR_HOST, 'port' => EPALS_SOLR_PORT, 'timeout' => 120);
        $this->solrClient = new \SolrClient($options);
    }


    // this call caches the results, do not set limit too high
    // if you dont want a limit use findCursorByMongo instead
    public function findByMongo($query, $limit = 10, $offset = 0, $sort = array('last_modified' => -1), $fields = array())
    {
        // convert the params to a json string and then take a sha1 hash of it
        // this will be the query id that we use for memcache
        $query_id_fields = array();
        $query_id_fields['type'] = 'MongoQuery';
        $query_id_fields['query'] = $query;
        $query_id_fields['limit'] = $limit;
        $query_id_fields['offset'] = $offset;
        $query_id_fields['sort'] = $sort;
        $query_id_fields['fields'] = $fields;
        $query_id_fields['ns_key'] = $this->get_ns_key();
        $query_id = sha1(json_encode($query_id_fields));

        //error_log("findByMongo: query_id: $query_id\n");

        if ($res = $this->memcache->get($this->memcacheid($query_id)))
        {
            //return $res;
        }

        $cursor = $this->findCursorByMongo($query, $fields);
        $cursor = $cursor->limit($limit)->skip($offset)->sort($sort);
        //var_dump($cursor);
        $res = array();
        foreach ($cursor as $rec)
        {
            array_push($res, $rec);
        }

        $this->memcache->set($this->memcacheid($query_id), $res, false, 60);
        return $res;
    }

    // returns a solr result
    public function findBySolrQuery($query = '', $filters = array(), $limit = 10, $offset = 0, $sort_field = null, $sort_order = null, $days_before = 0)
    {
        $query_id_fields = array();
        $query_id_fields['type'] = 'SolrQuery';
        $query_id_fields['query'] = $query;
        $query_id_fields['filters'] = $filters;
        $query_id_fields['limit'] = $limit;
        $query_id_fields['offset'] = $offset;
        $query_id_fields['sort_field'] = $sort_field;
        $query_id_fields['sort_order'] = $sort_order;
        $query_id = sha1(json_encode($query_id_fields));

        //if ($res = $this->memcache->get($this->memcacheid($query_id)))
        //{
        //  error_log("Returning cached search result");
        //  return $res;
        //}
    
        $q = new SolrQuery();
        //$query_string = $this->buildSolrQuery($query);
        if ($query)
        {
            $q->setQuery($query);
        } 
        else 
        {
            $q->setQuery('*');
        }
        array_push($filters, 'class:' . $this->get_called_class());
        if($days_before > 0)
        {
            $startDateString = date('Y-m-d'). 'T' . date('H:i:s') . 'Z';
            $endDate= date('Y-m-d H:i:s', strtotime("-" . $days_before . "days"));
            $endDateString = str_replace(' ', 'T', $endDate) . 'Z' ;
            array_push($filters, 'last_modified:[' .  $endDateString . ' TO ' . $startDateString . ']');
            //array_push($filters, 'last_modified:[2010-12-07T12:05:58Z TO 2011-08-22T18:07:11Z]');
            //array_push($filters, 'last_modified:[2010-12-07T12:05:58Z TO 2011-08-25T15:50:22Z]');
            //error_log('last_modified:[' . $endDateString . ' TO ' . $startDateString . ']');
        }
        
        foreach ($filters as $f)
        {
            $q->addFilterQuery($f);
        } 
        
        $q->setStart($offset);
        $q->setRows($limit);
        if (!is_null($sort_field))
        {
            if (!is_array($sort_field))
            {
                $q->addSortField($sort_field, $sort_order);
            } 
            else 
            {
                foreach ($sort_field as $k => $v)
                {
                    error_log("SORT BY: $k => $v");
                    $q->addSortField($k, $v);
                }
            }
        }
        $q->addField('*'); //->addField('description')->addField('visibility');
        $qr = $this->solrClient->query($q); 
        $res = $qr->getResponse();

        // convert the result to stdClass because we can't write to SolrObjects
        // also because once it comes out of memcache it's writeable anyways so its best
        // to keep behaviour consitent to avoid long debugging sessions ;-)
        $stored_res = new stdClass();
        $props = $res->getPropertyNames();
        foreach ($props as $p)
        {
            $param = substr($p, 0, -1);
            $stored_res->$param = $res[$param];
        }

        // store this query for 1 minute
        $this->memcache->set($this->memcacheid($query_id), $stored_res, false, 60);

        return $stored_res;
    }

    // this should be cached eventually.
    public function findOneByMongo($query = array(), $fields = null)
    {
        if (is_null($fields))
        {
            $r = $this->collection->findOne($query, $this->standard_fields());
        } 
        else 
        {
            $r = $this->collection->findOne($query, $fields);
        }
        return $r;
    }

    // not cached, used for direct mongo access on large sets of data
    public function findCursorByMongo($query = array(), $fields = array())
    {
        //var_dump($this->collection->getName());
        return $this->collection->find($query, $fields);
    }

    // probabaly wont need to override this ever, instead override insert_mongo, insert_solr, insert_memcache 
    public function insert()
    {
        if (is_null($this->id))
        {
            $uuid = new \UUID();
            $this->id = $uuid->create();
        }
        $this->insert_mongo(); 
        $this->insert_memcache();
        $this->insert_solr();
    }

    protected function insert_mongo($options = array())
    {
        $this->collection->insert($this->data, $options);
    }
 
    protected function insert_memcache()
    {
        $this->memcache->set($this->memcacheid($this->id), $this->data, false, 300);
    }

    public function get_called_class()
    {
        return get_class($this);
    }

    public function formatToUTC($passeddt) 
    {
        // Get the default timezone
        $default_tz = date_default_timezone_get();

        // Set timezone to UTC
        date_default_timezone_set("UTC");

        // convert datetime into UTC
        $utc_format = date("Y-m-d\TG:i:s\Z", $passeddt);

        // Might not need to set back to the default but did just in case
        date_default_timezone_set($default_tz);

        return $utc_format;
      }

    function skip_solr_key($key)
    {
        return false;
    }

    protected function insert_solr()
    {
        $doc = new \SolrInputDocument();
        $doc->addField('class', $this->get_called_class());
        foreach ($this->data as $key => $value) 
        {
            if ($this->skip_solr_key($key)) continue;
            if (is_object($value) && (get_class($value) == "MongoDate"))
            {
                $doc->addField($key, $this->formatToUTC($value->sec));
            } 
            else 
            {
                if (!is_array($value))
                {
                  if ($key == "district_id") $value = intval($value);
                  if (in_array($key, array('collaboration', 'languages', 'subjects'))) continue; // sometimes these fields are blank, we need to ignore them if they are not arrays or SOLR will cry foul
                  $doc->addField($key, $value);
                } else {
                  foreach ($value as $item)
                  {
                    $doc->addField($key, $item);
                  }
                }
            }
        }
        $this->before_save_solr($doc);
        $updateResponse = $this->solrClient->addDocument($doc);
        $this->solrClient->commit();
        //$this->solrClient->optimize();
    }
    
    public function before_save_solr($doc)
    { 

    }
    
    public function optimize()
    {
        $this->solrClient->optimize();
    }

    public function updateMongoByQuery($query, $record, $options = null)
    {
        if (is_null($options))
        {
            $options = array("safe" => true);
        }
        $this->collection->update($query, $record, $options);
    }

    public function kak()
    {
        $a = debug_backtrace();
        ob_start();
        var_dump($a);
        $foo = ob_get_contents();
        ob_end_clean();
        error_log("DIED: $foo\n");
        exit;
    }

    public function update()
    {
        if (is_null($this->id))
        {
            error_log("Can not update a record without an id");
            exit; //$this->kak();
        }
        $this->update_mongo();
        $this->update_solr();
        $this->delete_memcache($this->id);  //deleting is fine here, it will store a new on in memcache when it is requested again
    }

    // solr doesn't have an update yet, so delete & add is how it must be done for now.
    public function update_solr()
    {
        $this->delete_solr($this->id);
        $this->insert_solr();
    }

    public function update_mongo($options = null)
    {
        $query = array('id' => $this->id);
        $d = $this->get_data_copy();
        unset($d['_id']);
        $this->updateMongoByQuery($query, array('$set' => $d), $options);  // ugh, this $set thing is going to make removing fields impossible, but if we dont use $set we loose the extra_data (very large) fields
    }

    public function _delete()
    {
        if (is_null($this->id))
        {
          error_log("Can not delete a record without an id");
          exit;
        }
        $this->delete_mongo($this->id);
        $this->delete_memcache($this->id);
        $this->delete_solr($this->id);
    }

    public function delete()
    {
        $this->_delete();
    }

    function delete_mongo($id, $options = null)
    {
        if (is_null($options))
        {
          $options = array("fsync" => true);
        }

        $res = $this->collection->remove(array('id' => $id), $options);
        return $res;
    }
 
    // pass it an id and it will be deleted from solr only
    function delete_solr($id)
    {
        $this->solrClient->deleteById($id);
        $this->solrClient->commit();
    }

    function delete_memcache($id)
    {
        $this->memcache->delete($this->memcacheid($id));
    }

    // takes a mongo query, pulls all tehir ids, deletes them from memcache, solr then mongo
    function deleteByQuery($query, $options = null) 
    {
        if (is_null($options))
        {
            $options = array("safe" => true);
        }

        $cursor = $this->collection->find($query);
        foreach ($cursor as $record)
        {
            if (isset($record['id']))
            {
                $this->delete_memcache($record['id']); 
                $this->delete_solr($record['id']); 
            }
        }

        $res = $this->collection->remove($query, $options);

        return $res;
    }

    // the key to cycle the nameSpaces, when we have to invalidate the cache after deleting or updating
    protected function get_ns_key()
    {
        if (isset($this->ns_key))
        {
            return $this->ns_key;
        }
        $this->ns_key = $this->memcache->get('ns_key_' . $this->get_called_class());
        if($this->ns_key===false) 
        {
            $nk = 'ns_key_' . $this->get_called_class();
            $nv = rand(1,100000);
            $this->memcache->set($nk,$nv,false,300);
            $this->ns_key = $nv;
        }
        return $this->ns_key;
    }

    // increment the NS id to clear all old records
    public function invalidate()
    {
        unset($this->ns_key);
        $this->memcache->increment('ns_key_' . $this->get_called_class());
    }

    // so there are no conflicts or collisions with keys in memcached
    protected function memcacheid($id)
    {
        return $this->get_called_class() . '_' . $this->get_ns_key() . '.' . $id;
    }

    public function standard_fields()
    {
        $x = array();
        foreach ($this->extra_fields() as $f)
        {
            $x[$f] = 0;
        }
        return $x;
    }

    public function extra_fields()
    {
        return array('moderation_log', 'clickthroughs');
    }

    public function findById($id)
    {
        if ($res = $this->memcache->get($this->memcacheid($id)))
        {
            $this->data = $res;
            //error_log("Returning stored single record by id from memcache");
            return TRUE;
        }

        if ($res = $this->collection->findOne(array('id' => $id), $this->standard_fields()))
        {
            foreach ($res as $key => $value)
            {
                $this->data[$key] = $value;
            }

            if (!$this->memcache->set($this->memcacheid($id), $this->data, false, 60))
            {
                //error_log("Could not store single record by id in memcache, is it down?");
            }
            return TRUE;

        } 
        else 
        {
           //error_log("Could not find single record by id $id");
           return FALSE;
        }
    }

    public function count($query = array()) 
    {
        $count = $this->collection->count($query);
        if (is_null($count)) 
        {
            $count = 0;
        }
        return $count;
    }
}
}
