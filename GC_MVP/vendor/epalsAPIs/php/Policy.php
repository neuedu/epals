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

require_once("vendor/autoload.php");
require_once("Role.php");
require_once('classes/Record.php');

class Policy extends Record {
    protected $acl;
    protected $s_acl;
    protected $resource;
    protected $s_resource;
    public $id;
    
    function __construct($id) {
        if (empty($id) || is_null($id)) {
            throw new Exception("Must provide a name for this policy");
        }
        parent::__construct();
        $this->id = $id;
        $ini = parse_ini_file('classes/Config.ini',TRUE);
        $roles = $ini["roles"]["role"];
        $res = parent::get();
        if (!$res) {
            $this->acl = new Zend\Permissions\Acl\Acl();
            $acl = $this->acl;
            // potential bug here. id needs to be the uuid i think. 
            $this->resource = new Zend\Permissions\Acl\Resource\GenericResource($this->id);
            $acl->addResource($this->resource);
            foreach ($roles as $role) {
                $r = new Role($role);   
                $r->add();
                $acl->addRole($r->getpRole($role));
                unset($r);
            }
        } else {
            $this->acl = unserialize($this->s_acl);
            $this->resource = unserialize($this->s_resource);
        }
    }
    
    function allow($role,$privilege,$object = null) {
        if (empty($role) || is_null($role)) {
            throw new Exception("You must provide a role.");
        }
        if (empty($privilege) || is_null($privilege)) {
            throw new Exception("You must provide a privilege");
        }
        return $this->_addPolicy("allow", $role, $privilege,$object);
    }
    
    function deny($role,$privilege,$object = null) {
        if (empty($role) || is_null($role)) {
            throw new Exception("You must provide a role.");
        }
        if (empty($privilege) || is_null($privilege)) {
            throw new Exception("You must provide a privilege");
        }
        return $this->_addPolicy("deny", $role, $privilege,$object);
    }
    
    private function _addPolicy($type,$role,$privilege,$object = null) {
        $rp = $role->getpRole($role);
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
        $this->s_acl = serialize($this->acl);
        $this->s_resource = serialize($this->resource);
        parent::add();
    }
    
    public function getAcl() {
        return $this->acl;
    }

    public function getResource() {
        return $this->resource;
    }
    
    public function isAllowed($role,$privilege,$object = null) {
        if (empty($role) || is_null($role)) {
            throw new Exception("You must provide a role.");
        }
        if (empty($privilege) || is_null($privilege)) {
            throw new Exception("You must provide a privilege");
        }
        if (is_null($object)) {
            $object = $this->getResource();
        }
        $r = new Role($role);
        $rp = $r->getpRole($role);
        $res = $this->acl->isAllowed($rp, $object, $privilege);
        return $res;
    }
            
    
}
