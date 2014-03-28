<?php

require_once("record/ConfigStorage.php");

class Query 
{

  public $mongo;
  public $mongodb;
  public $ns_key;

  public $memcache;
  public $solrClient;
  public $collection;
  public $transformed;
  public $class;
  public $mongoCollectionName;
  public $db;
  
  // By default, we query SOLR
  
  function __construct($queryType = "solr", $classToQuery, $db = null) 
  {
    $this->db = $db;
    $this->init_memcache();
    if ($queryType == "mongo") {
        $this->mongoCollectionName = $classToQuery;
        $this->init_mongo();
    } else {
        $this->init_solr();
        $this->class = $classToQuery;
    }
  }

  protected function init_memcache()
  {
    $this->memcache = new Memcache();
    $this->memcache->pconnect(EPALS_MEMCACHE_HOST, EPALS_MEMCACHE_PORT);
  }

  protected function init_mongo()
  {
    $this->mongo = new Mongo(EPALS_MONGO_CONNECTION_STRING);
    $this->mongodb = $this->mongo->selectDB($this->db);
    $this->collection = $this->mongodb->selectCollection($this->mongoCollectionName);
  }

  protected function get_collection_name()
  {
    return $this->mongoCollectionName;
  }

  protected function init_solr()
  {
    //$options = array('hostname' => 'ec2-23-22-244-128.compute-1.amazonaws.com', 'port' => EPALS_SOLR_PORT, 'timeout' => 120);
    $options = array('hostname' =>  EPALS_SOLR_PROJECT_HOST, 'port' => EPALS_SOLR_PORT, 'timeout' => 120);
    $this->solrClient = new SolrClient($options);
  }

  // isolates records in solar by using the class field, string value of get_called_class();
  // if you overload get_called_class make sure it doesn't break this solr query string
  protected function buildSolrQuery($query)
  {
     $q = 'class:' . $this->class;
     if ($query != '')
     {
       $q .= ' AND ' . $query;
     }
     return $q;
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
    $query_id = sha1(json_encode($query_id_fields));

    error_log("findByMongo: query_id: $query_id\n");

    $cursor = $this->findCursorByMongo($query, $fields);
    $cursor = $cursor->limit($limit)->skip($offset)->sort($sort);
    $res = array();
    foreach ($cursor as $rec) {
      array_push($res, $rec);
    }

    //$this->memcache->set($this->memcacheid($query_id), $res);

    return $res;
  }

  public function memcacheid($id)
  {
    return $this->class . '_' . $id;
  }

  // returns a solr result
  public function findBySolrQuery($query = '', $filters = array(), $limit = 10, $offset = 0, $sort_field = null, $sort_order = null) {
    $query_id_fields = array();
    $query_id_fields['type'] = 'SolrQuery';
    $query_id_fields['query'] = $query;
    $query_id_fields['filters'] = $filters;
    $query_id_fields['limit'] = $limit;
    $query_id_fields['offset'] = $offset;
    $query_id_fields['sort_field'] = $sort_field;
    $query_id_fields['sort_order'] = $sort_order;
    $query_id = sha1(json_encode($query_id_fields));

if (FALSE) {
    if ($res = $this->memcache->get($this->memcacheid($query_id)))
    {
      error_log("Returning cached search result");
      return $res;
    }
}
    $q = new SolrQuery();
    //$query_string = $this->buildSolrQuery($query);

    //error_log("SOLR QUERY STRING: $query_string");

    if ($query)
    {
      $q->setQuery($query);
    } else {
      $q->setQuery('*');
    }

    array_push($filters, 'class:' . $this->class);
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
      } else {
        foreach ($sort_field as $k => $v)
        {
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
  public function findOneByMongo($query = array(), $fields = array())
  {
      return $this->collection->findOne($query, $fields);
  }

  // not cached, used for direct mongo access on large sets of data
  public function findCursorByMongo($query = array(), $fields = array())
  {
      return $this->collection->find($query, $fields);
  }

  public function mongoQueryIn($key, $value) {
        $res = array();
        $cursor = $this->collection->find(array($key => array('$in' => array($value))));
        foreach ($cursor as $doc) {
            array_push($res, $doc);
        }
        return $res;
  }

  public function mongoCount($query = array()) {
        $count = $this->collection->count($query);
        if (is_null($count)) {
            return 0;
        }
        return $count;
  }

  function mongoFetch($queryKey, $queryValue, $sortKey = "createDate", $skip = 0, $limit = null) {
        $res = array();
        $cursor = $this->collection->find(array($queryKey => $queryValue))->limit($limit)->skip($skip);
        //$cursor = $this->collection->sort(array("createDate" => 1));
        foreach ($cursor as $doc) {
            array_push($res, $doc);
        }
        return $res;
   }


}
