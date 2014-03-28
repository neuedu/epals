<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("../php/tenant.php");
require_once("../php/school.php");
require_once("../php/course.php");
require_once("../php/teacher.php");
require_once("../php/student.php");
require_once("../php/classes/parental.php");
require_once("../php/teachersgroup.php");
require_once("../php/group.php");
require_once("../php/graphmapper.php");
require_once("php/testutility.php");
require_once("../php/config.php");
/**
 * Description of generaltest
 *
 * @author nehalsyed
 */
class GeneralTest {
    //put your code here

    private $domain;   
    private $emaildomain;  
    private $externalemaildomain;
    private $tenantName;  
    private $graphmapper;
 
    function GeneralTest(){
       
        $this->tenanttoken = strtolower(TestUtility::generateRandomString(5));
        $this->domain = $this->tenanttoken."town.test.com";   
        $this->emaildomain = $this->tenanttoken."town.mail.test.com";  
        $this->externalemaildomain = $this->tenanttoken.".yahoo.com";
        $this->tenantName = $this->tenanttoken."town";  
       
        $this->graphmapper = new GraphMapper($this->domain);
        
    }
 
     function setup() {
         
         $this->createTenant();
         $this->createSchool();
         $this->createCourse();
         
     }
    
    function createTenant() {
       
        $t = new Tenant();
     
        $t->setDomain($this->domain);
        $t->setEmailDomain($this->emaildomain);
        //$t->setPublished('false');
        $t->setName($this->tenantName);
        
        $result = $t->add();
         
        print_r($result);
    }
    
    
    function createSchool() {
       
      $s = new School();
      $s->setDescription('Test School desc');
      $s->setSchoolId("School1"); // School ExternalID
      $s->setName("Herndon public School");
      $result = $s->add($this->domain);
         
      print_r($result);
    }
    
    function createCourse() {
       
      $c = new Course();
      $c->setCourseId("Course1"); // Course externalID
      $c->setTitle("Maths 101");
    
      $result = $c->add($this->domain, "School1");
         
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
        
        
        $t2 = new Teacher();
        $t2->setAccount("wburr@".$this->domain);
        $t2->setEPalsEmail("wburr@".$this->emaildomain);
        $t2->setExternalEmail("wburr@".$this->externalemaildomain);
        $t2->setUserId("58_3");
        $t2->setFirstName("Wanda");
        $t2->setLastName("Burr");
        $t2->setPassword("Learning6");
        
        
        $result2 = $t2->add();
        print_r($result2);
       
       }
    
    function createStudents()
    {
        
        $st1 = new Student();
        $st1->setAccount("matiasb@".$this->domain);
        $st1->setEPalsEmail("matiasb@".$this->emaildomain);
        $st1->setExternalEmail("matiasb@".$this->externalemaildomain);
        $st1->setUserId("58_2_1");
        $st1->setFirstName("Matias");
        $st1->setLastName("Gray");
        $st1->setPassword("Learning51");
        $st1->setRawDob("19960101");
        $st1->setGrade("11");
        
        $ressult1 = $st1->add();
        print_r($ressult1) ;
         
        $st2 = new Student();
        $st2->setAccount("blancap@".$this->domain);
        $st2->setEPalsEmail("blancap@".$this->emaildomain);
        $st2->setExternalEmail("blancap@".$this->externalemaildomain);
        $st2->setUserId("58_2_2");
        $st2->setFirstName("Blanca");
        $st2->setLastName("Pizarro");
        $st2->setPassword("Learning51");
        $st2->setRawDob("19960101");
        $st2->setGrade("12");
        
        $ressult2 = $st2->add();
        print_r($ressult2) ;

    }
    
    function addModeratorToStudent(){
        
        $t1 = new Teacher("hcruz@".$this->domain);
        $result1 = $t1->addStudent("matiasb@".$this->domain);
        print_r($result1);
        
        $t2 = new Teacher("wburr@".$this->domain);
        $result2 = $t2->addStudent("blancap@".$this->domain);
        print_r($result2);
        
    }
    
    
    function createParents()
    {
        $p1 = new Parental();
        $p1->setAccount("belle@".$this->domain);
        $p1->setEPalsEmail("belle@".$this->emaildomain);
        $p1->setExternalEmail("belle@".$this->externalemaildomain);
        $p1->setUserId("P58_2_1");
        $p1->setFirstName("Edward");
        $p1->setLastName("Bell");
        $p1->setPassword("Password123");
        
        $result1 = $p1->add();
        print_r($result1);
        
        $p2 = new Parental();
        $p2->setAccount("pizarrok@".$this->domain);
        $p2->setEPalsEmail("pizarrok@".$this->emaildomain);
        $p2->setExternalEmail("pizarrok@".$this->externalemaildomain);
        $p2->setUserId("P58_3_1");
        $p2->setFirstName("Kathryn");
        $p2->setLastName("Pizarro");
        $p2->setPassword("Password123");
        
        $result2 = $p2->add();
        print_r($result2);
        
    }
    
    function addParentToStudents(){
        
        $s1 = new Student("matiasb@".$this->domain);
        $result1= $s1->addParent("belle@".$this->domain);
        print_r($result1);
        
        $s2 = new Student("blancap@".$this->domain);
        $result2 = $s2->addParent("pizarrok@".$this->domain);
        print_r($result2);
        
    }
    
    
    function createTeachersGroups() 
    {
         $g1 = new TeachersGroup();
         $g1->setName('Hector_Cruz_Group');
         $result1 = $g1->add($this->domain);
         
         print_r($result1);
         
         $g2 = new TeachersGroup();
         $g2->setName('Wanda_Burr_Group');
         $result2 = $g2->add($this->domain);
         
         print_r($result2);
    }
    
    function addUserToTeachersGroup() 
    {
          $g1 = new TeachersGroup($this->domain, 'Hector_Cruz_Group');
          $g1->addTeacherToGroup("hcruz@".$this->domain);
          $result1 = $g1->addStudentToGroup("matiasb@".$this->domain);
          print_r($result1);
          
          $g2 = new TeachersGroup($this->domain, 'Wanda_Burr_Group');
          $g2->addTeacherToGroup("wburr@".$this->domain);
          $result2 = $g2->addStudentToGroup("blancap@".$this->domain);
          print_r($result2);
          
    }
    
    
    function createGroupInTenant() 
    {
         $g1 = new Group();
         $g1->setName('Maths Project');
         $g1->setExternalId("Math_P_1");
       
         $groupuuid = $g1->add($this->domain);
         
         echo $groupuuid;
         
         $g2 = new Group($groupuuid);
         $result1 = $g2->addOwner("hcruz@".$this->domain);
          print_r($result1);
         
         $result2 = $g2->addAssistant("wburr@".$this->domain);
         print_r($result2);
         
         $result3 = $g2->addMember("matiasb@".$this->domain);
         print_r($result3);
         
         $result4 = $g2->addObserver("blancap@".$this->domain);
         print_r($result4);
    }
    
    
    
}

try
{
    
 $gt = new GeneralTest(); 
 $gt->setup();
 $gt->createTeachers();
 $gt->createStudents();
 $gt->addModeratorToStudent();
 $gt->createParents();
 $gt->addParentToStudents();
 $gt->createTeachersGroups();
 $gt->addUserToTeachersGroup();
 $gt->createGroupInTenant();
 
 
}
catch (Exception $e){
    echo $e;
    
}


?>
