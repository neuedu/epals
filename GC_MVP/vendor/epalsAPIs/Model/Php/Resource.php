<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Resource
 *
 * @author root
 */

require_once("Record.php");


class Resource extends Record {
    public $id;
    public $name;
    public $meta;
    public $description;
    public $url;
    public $creator;
    public $owner;
    
    function __construct() {
        parent::__construct();
        $this->collection = $this->m->selectCollection("gc", "resource");
    }
}


?>
