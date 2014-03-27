<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of testschool
 *
 * @author nehalsyed
 */

require_once("../php/school.php");

class TestSchool {
    //put your code here

  public function testAddSchool()
  {
      
      $u = new School();
      $u->setDescription('Test School desc');
      $u->setSchoolId('School1');
      $u->setName('School 1');
      echo $u->add('test.nehal.com');
        
  }   
  
  public function testUpdateSchool()
  {
      
      $u = new School('test.nehal.com', 'School1');
      $u->setSchoolId('School1');
      $u->setName('School 1aa');
      $result = $u->update();
      $print_r($result);  
  }   
  
  public function testAddUserToSchool()
  {
      $s = new School('test.nehal.com', 'School1');
      $result = $s->addUserToSchool('thiagoa_1@test.nehal.com', 'Student');
      
      $print_r($result);  
  }
  
  public function testExistsSchool()
  {
      $result1 = new School('test.nehal.com', 'School1');
      $result2 = new School('anytown.epals.com', '25');
      
      $print($result1); 
      $print($result2); 
  }
    
}

try{
 $t = new TestSchool(); 
 //$t->testAddSchool();
 //$t->testUpdateSchool();
 //$t->testAddUserToSchool();
 $t->testExistsSchool();
 
}  catch (Exception $e){
    echo $e;
    
}
 ?>
