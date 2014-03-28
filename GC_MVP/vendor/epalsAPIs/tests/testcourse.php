<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("../php/course.php");

/**
 * Description of testcourse
 *
 * @author nehalsyed
 */
class TestCourse {
    //put your code here
    
  public function testAddCourse()
  {
      
      $u = new Course();
      $u->setCourseId("Course1");
      $u->setTitle("Course 1");
    
      $result = $u->add('test.nehal.com', 'School1');
      print_r($result);
        
  }    
  
  public function testUpdateCourse()
  {
      
      $u = new Course('test.nehal.com', 'School1', 'Course1');
      $u->setTitle("Course 2");
    
      $result = $u->update();
      print_r($result);
        
  }  
  
}


try{
    
 $t = new TestCourse(); 

   $t->testAddCourse();
 //$t->testUpdateCourse();
 
 }catch (Exception $e){
    echo $e;
 }

?>
