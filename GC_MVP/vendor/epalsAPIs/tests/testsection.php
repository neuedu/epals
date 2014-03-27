<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("../php/section.php");


/**
 * Description of testsection
 *
 * @author nehalsyed
 */
class TestSection {
    //put your code here

    
  public function testAddSection()
  {
      
      $u = new Section();
      $u->setSectionId('School_1_Section_1');
      $u->add('test.nehal.com', 'School1', 'Course1');
      
        
       // echo $u->add();
        
  } 
  
  public function testUpdateSection()
  {
      
      $u = new Section('test.nehal.com', 'School1', 'Course1', 'School_1_Section_1');
      $u->setSectionId('School_1_Section_1');
      $u->update();
      
        
       // echo $u->add();
        
  } 
  
  
   public function testAddStudentToSection()
  {
      
      $u = new Section('test.nehal.com', 'School1', 'Course1', 'School_1_Section_1');
      print $u->addSectionEnrollment('thiagoa_1@test.nehal.com', 'Student');
        
        
  }
  
  public function testAddTeacherToSection()
  {
      
      $u = new Section('test.nehal.com', 'School1', 'Course1', 'School_1_Section_1');
      print $u->addSectionEnrollment('thiagoa_teacher@test.nehal.com', 'Teacher');
        
        
  }
    
}


try{
    

 $t = new TestSection(); 
 //$t->testAddSection();
// $t->testUpdateSection();
 //$t->testAddStudentToSection();
$t->testAddTeacherToSection();
 
}
catch (Exception $e){
    echo $e;
    
}

?>
