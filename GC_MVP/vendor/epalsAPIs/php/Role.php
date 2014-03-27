<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Role
 *
 * @author shaz
 */

require_once("vendor/autoload.php");
require_once("classes/Record.php");

class Role extends Record {
    
    public $id;
    protected $pRole;
    
    function __construct($role) {
        if(!isset($role) || trim($role) === '')
    throw new Exception("Role name parameter is empty");
        $this->id = $role;
        parent::__construct();
    }
    
    function add() {
        $pRole = new Zend\Permissions\Acl\Role\GenericRole($this->id);
        $this->pRole = serialize($pRole);
        parent::add();
    }
    
    function getpRole($id) {
        /*
        $this->__construct($id);
        $this->get();
        $pRole = unserialize($this->pRole);
        return $pRole;
         * 
         */
        return $this->pRole;
    }
}
