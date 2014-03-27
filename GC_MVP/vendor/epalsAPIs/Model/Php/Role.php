<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Role
 *
 * @author root
 */

//require_once("vendor/autoload.php");
require_once("MongoDocument.php");

class Role extends MongoDocument {
    
    public $id;
    protected $pRole;
    
    function __construct($id) {
        parent::__construct();
        
        $this->collection = $this->m->selectCollection($this->tenant, "role");
        $this->id = $id;
        $this->fetchByID($id);
        
    }
    
    function add() {
        $pRole = new Zend\Permissions\Acl\Role\GenericRole($this->id);
        $this->pRole = serialize($pRole);
        parent::add();
    }
    
    function getpRole() {
        $this->fetchByID($this->id);
        $pRole = unserialize($this->pRole);
        return $pRole;
    }
}
