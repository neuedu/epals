<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of regflowtest
 *
 * @author nehalsyed
 */

require_once("../php/tenant.php");
require_once("../php/school.php");
require_once("../php/course.php");
require_once("../php/teacher.php");
require_once("../php/student.php");
require_once("../php/section.php");
require_once("../php/graphmapper.php");
require_once("php/testutility.php");
require_once("../php/config.php");



class RegflowTest {
    //put your code here
    
    private $domain;   
    private $emaildomain;  
    private $externalemaildomain;
    private $tenantName;  
    private $graphmapper;
    
    private $schoolId;
    private $courseId;
    private $sectionId;
    private $teacherFName;
    private $teacherLName;
    
    
 
    function RegflowTest(){
       
        $this->tenanttoken = strtolower(TestUtility::generateRandomString(5));
        $this->domain = $this->tenanttoken.".test.com";   
        $this->emaildomain = $this->tenanttoken.".mail.test.com";  
        $this->externalemaildomain = $this->tenanttoken.".yahoo.com";
        $this->tenantName = $this->tenanttoken."town";  
        $this->graphmapper = new GraphMapper($this->domain);
        
        
        // $this->teacherUserName= "nmsyed@".$this->domain;
         $this->teacherFName= "Hector";
         $this->teacherLName= "Cruz";
         $this->schoolId = "Fairfax_County_School";
         $this->courseId= "Maths101";
         $this->sectionId = $this->teacherFName.'_' . $this->teacherLName. '_' . $this->courseId;
         
    
        
    }
    
    function createTenant() {
       
        $t = new Tenant();
     
        $t->setDomain($this->domain);
        $t->setEmailDomain($this->emaildomain);
        $t->setName($this->tenantName);
        $result = $t->add();
        print_r($result);
    }
    
    
    function createSchool() {
       
      $s = new School();
      $s->setDescription('Test School desc');
      $s->setSchoolId($this->schoolId); // School ExternalID
      $s->setName("Fairfax County School");
      $result = $s->add($this->domain);
      print_r($result);
    }
    
    function createCourse() {
       
      $c = new Course();
      $c->setCourseId($this->courseId); // Course externalID
      $c->setTitle("Maths 101");
      $result = $c->add($this->domain, $this->schoolId);
      print_r($result);
    }
    
    function createSection() {
       
      $c = new Section();
      $c->setSectionId($this->sectionId); // Course externalID
      $result = $c->add($this->domain, $this->schoolId, $this->courseId);
      print_r($result);
    }
    
    function createTeachers() {
     
        
        $t1 = new Teacher();
        $t1->setAccount("hcruz@".$this->domain);
        $t1->setEPalsEmail("hcruz@".$this->emaildomain);
        $t1->setExternalEmail("hcruz@".  $this->externalemaildomain);
        $t1->setUserId("58_2");
        $t1->setFirstName("Hector");
        $t1->setLastName("Cruz");
        $t1->setPassword("Learning4");

        $result1 = $t1->add();
        
        print_r($result1);
       
       }
       
       function addUserToSection() 
       {
          $g1 = new Section($this->domain, $this->schoolId, $this->courseId, $this->sectionId);
          $result1 = $g1->addSectionEnrollment("hcruz@".$this->domain, 'Teacher');
          print_r($result1);
          
       }
    
}

try
{
    
 $gt = new RegflowTest(); 
 $gt->createTenant();
 $gt->createSchool();
 $gt->createCourse();
 $gt->createSection();
 $gt->createTeachers();
 $gt->addUserToSection();
 
}
catch (Exception $e){
    echo $e;
    
}

?>
