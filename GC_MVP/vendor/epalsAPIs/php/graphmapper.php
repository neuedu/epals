<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('tenant.php');

/**
 * Description of graphmapper
 *
 * @author nehalsyed
 */
class GraphMapper {
    //put your code here

    private $tenantDomain;
    private $ini;
    
    function __construct($tenantDomain = NULL) {
          
        $this->ini = parse_ini_file('config.ini',TRUE);
        
        if(!empty($tenantDomain))
        {
            $this->tenantDomain = $tenantDomain;
        }
        else 
        {
            $this->tenantDomain = $this->ini["tenant"]["name"];
        }
        
    }
        
    function getDefaultSchool()
    {
        
        return $this->ini["school"]["name"];
        
    }
    
    function getDefaultCourse()
    {
        
        return $this->ini["course"]["name"];
        
    }
    
    function getDefaultSection()
    {
        return $this->ini["section"]["name"];
        
    }
    
    function getTenantDomain()
    {
        return $this->tenantDomain;
        
    }
    
}

?>
