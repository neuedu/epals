<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestTenant
 *
 * @author nehalsyed
 */

require_once("../php/tenant.php");


class TestTenant{
    //put your code here

    
 public function testAddTenant()
  {
      
        $u = new Tenant();
     
        $u->setDomain('test.nehal.com');
        $u->setEmailDomain('mail.test.nehal.com');
        $u->setPublished('false');
        $u->setName("Test Global Community");
        echo $u->add();
        
  }   
    
  public function testUpdateTenant()
  {
      
      $u = new Tenant('test.nehal.com');
        $u->setAppsEnabled('');
        $u->setDomain('test.nehal.com');
        $u->setEmailDomain('mail.test.nehal.com');
        $u->setName("ePals Global Community1");
        echo $u->update();
        
  }
  
 
  public function testSetupDefaults()
  {
      
        $u = new Tenant('test.nehal.com');
        $u->setupDefaults();
        
  }
}

try{
    

 $t = new TestTenant(); 
 //$t->testAddTenant();
 //$t->testUpdateTenant();
 $t->testSetupDefaults();

 
}
catch (Exception $e){
    echo $e;
    
}
?>
