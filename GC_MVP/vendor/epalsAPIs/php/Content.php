<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Content
 *
 * @author root
 */
require_once("classes/Record.php");
require_once("tenant.php");

class Content extends Record {
    
    
    public $id;
    public $title;       // required
    public $metadata;
    public $url;        
    public $data;       // required
    public $author;     // required
    public $tenant;     // required
    protected $mode;
    
    function __construct($id = null) {
        parent::__construct();
        $this->mode = "add";
        if (isset ($id)) {
            $this->id = $id;
            $this->get();
            $this->mode = "update";
        }
    }
    
    function addMetadata($key,$value) {
         
        if(!isset($this->tenant) || trim($this->tenant)==='')
            throw new Exception("Content tenant parameter is empty");
        
        $T = new Tenant();
        $T->loadTenant($this->tenant);
        if($T->getDomain() != $this->tenant) {
            throw new Exception("invalid tenant url");
        }
        
        $tmp = array($key => $value);
        switch ($this->mode) {
            case "add": 
                $this->metadata = $tmp;
                $res = parent::add();
                break;
            case "update":
                $this->metadata = array_merge($this->metadata,$tmp);
                $res = parent::update();
                break;
        }
    }
    
    public function getTenant() {
        return $this->tenant;
    }
    
    public function setTenant($tenant) {
        
        $T = new Tenant();
        $T->loadTenant($tenant);
        if($T->getDomain() != $tenant) {
            throw new Exception("invalid tenant url");
        }
        
        $this->tenant = $tenant;
    }
    
    public function getName() {
        return $this->title;
    }

    public function setName($name) {
        $this->title = $name;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }
    
    public function add() {
        
        if(!isset($this->tenant) || trim($this->tenant)==='')
            throw new Exception("Content tenant parameter is empty");
        
        $T = new Tenant();
        $T->loadTenant($this->tenant);
        if($T->getDomain() != $this->tenant) {
            throw new Exception("invalid tenant url");
        }
        
        $T = new Tenant();
        $T->loadTenant($this->tenant);
        if($T->getDomain() != $this->tenant) {
            throw new Exception("invalid tenant url");
        }
        
        if (is_null($this->data) || empty($this->data)) {
            throw new Exception("Must have content to save.");
        }
        if (is_null($this->author) || empty($this->author)) {
            throw new Exception("Author cannot be empty.");
        }
        if (is_null($this->title) || empty($this->title)) {
            throw new Exception("Title cannot be empty.");
        }
        
        
        return parent::add();
    }
    
    function update() {
        
        if(!isset($this->tenant) || trim($this->tenant)==='')
            throw new Exception("Content tenant parameter is empty");
        
        $T = new Tenant();
        $T->loadTenant($this->tenant);
        if($T->getDomain() != $this->tenant) {
            throw new Exception("invalid tenant url");
        }
        
        if (is_null($this->data) || empty($this->data)) {
            throw new Exception("Must have content to save.");
        }
        if (is_null($this->author) || empty($this->author)) {
            throw new Exception("Author cannot be empty.");
        }
        if (is_null($this->title) || empty($this->title)) {
            throw new Exception("Title cannot be empty.");
        }
        return parent::update();
    }
    


    
    
    
    
}

?>
