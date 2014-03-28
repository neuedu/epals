<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Policy
 *
 * @author root
 */

//require_once("vendor/autoload.php");
require_once("Role.php");

class Policy {
    protected $acl;
    protected $resource;
    
    function __construct($id) {
        $ini = parse_ini_file('Config.ini',TRUE);
        $roles = $ini["roles"]["role"];
        $this->acl = new Zend\Permissions\Acl\Acl();
        $acl = $this->acl;
        $this->resource = new Zend\Permissions\Acl\Resource\GenericResource($id);
        $acl->addResource($this->resource);
        foreach ($roles as $role) {
            $r = new Role($role);
            $r->add();
            $acl->addRole($r->getpRole());
            unset($r);
        }
    }
    
    function allow($role,$privilege,$object = null) {
        return $this->_addPolicy("allow", $role, $privilege,$object);
    }
    
    function deny($role,$privilege,$object = null) {
        return $this->_addPolicy("deny", $role, $privilege,$object);
    }
    
    private function _addPolicy($type,$role,$privilege,$object = null) {
        $rp = $role->getpRole();
        if (is_null($object)) {
            $object = $this->getResource();
        }
        switch ($type) {
            case "allow":
                $this->acl->allow($rp,$object,$privilege);
                break;
            case "deny":
                $this->acl->deny($rp,$object,$privilege);
                break;
        }
    }
    
    function get() {
        return $this->acl;
    }
    
    function save() {
        $moo = serialize($this->acl);
        return $moo;
    }
    
    public function getAcl() {
        return $this->acl;
    }

    public function getResource() {
        return $this->resource;
    }
    
    public function isAllowed($role,$privilege,$object = null) {
        if (is_null($object)) {
            $object = $this->getResource();
        }
        $res = $this->acl->isAllowed($role, $object, $privilege);
        return $res;
    }
            
    
}
