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
require_once("Record.php");

class Content extends Record {
    
    
    public $id;
    public $name;       // required
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
            $this->get();
            $this->mode = "update";
        }
    }
    
    function addMetadata($key,$value) {
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
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
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


    
    
    
    
}

?>
