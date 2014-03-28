<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Event
 *
 * @author root
 */

require_once("classes/Record.php");

class Event extends Record {
    
    public $id;
    protected $type;
    protected $data;
    protected $timestamp;
    protected $callback;
    
    function __construct($type = null, $data = null, $callback = null) {
        parent::__construct();
        $this->timestamp = time();
        if ($type) {
            $this->type = $type;
        }
        if ($data) {
            $this->data = $data;
        }
        if ($callback) {
            $this->callback = $callback;
        }
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
    
    public function setType($type) {
        $this->type = $type;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setCallback($callback) {
        $this->callback = $callback;
    }
    
    public function add() {
        if (isset($this->type) && 
            trim($this->type) != '' &&
            isset($this->data) && 
            trim($this->data) != '') {
            return parent::add();
        }
        else {
            throw new Exception("Either of these parameters are empty: type, data");
        }            
    }
}
