<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BootstrapDB
 *
 * @author root
 */

require_once('vendor/autoload.php');

class BootstrapDB {
    
    protected $tenant;
    protected $elasticIndexes;
    
    function __construct() {
        $ini = parse_ini_file('Config.ini',TRUE);
        $this->elasticSearchClient = new Elasticsearch\Client();
        $tenant = $ini["tenant"]["name"];
        $elasticIndexes = $ini["elasticindexes"]["types"];
        $this->elasticIndexes = explode(",", $elasticIndexes);
        $this->tenant = $tenant;
    }
    
    function createElasticIndex () {
        $indexParams['index'] = $this->tenant;
        $this->elasticSearchClient->indices()->create($indexParams);
    }
}

?>
