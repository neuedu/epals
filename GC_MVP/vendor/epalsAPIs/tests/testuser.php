<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestUser
 *
 * @author shaz
 * 
 */

require_once("../php/user.php");
require_once("../php/student.php");
require_once("../php/parental.php");
require_once("../php/school.php");
class TestUser {

    function addUser() {
        $u = new User();
        $u->setAccount("thiagoa_1@test.nehal.com");
        $u->setEPalsEmail("thiagoa_1@mail.test.nehal.com");
        $u->setExternalEmail("thiagoa_1@mac.com");
        $u->setUserId("2_812_1_1_11");
        $u->setFirstName("Nehal");
        $u->setLastName("Syed");
        $u->setPassword("Password123");
        $u->setRawDob("19960101");
        $u->setGrade("11");
        $r = array("Student");
        $u->setRole($r);
        $res = $u->add();
        print $res;
    }
    
    
    function addStudent() {
        $u = new Student();
        $u->setAccount("thiagoa_student@test.nehal.com");
        $u->setEPalsEmail("thiagoa_student@mail.test.nehal.com");
        $u->setExternalEmail("thiagoa_student@mac.com");
        $u->setUserId("2_812_1_1_2");
        $u->setFirstName("Nehal");
        $u->setLastName("Syed");
        $u->setPassword("Password123");
        $u->setRawDob("19960101");
        $u->setGrade("11");
        
        $res = $u->add();
        print $res;
    }
    
    
    
    function addTeacher() {
        $u = new Teacher();
        $u->setAccount("thiagoa_teacher@test.nehal.com");
        $u->setEPalsEmail("thiagoa_teacher@mail.test.nehal.com");
        $u->setExternalEmail("thiagoa_teacher@mac.com");
        $u->setUserId("2_812_1_1_3");
        $u->setFirstName("Nehal");
        $u->setLastName("Syed");
        $u->setPassword("Password123");
        
        
        $res = $u->add();
        print $res;
    }
    
    
    function addParent() 
    {
        $u = new Parental();
        $u->setAccount("thiagoa_parent@test.nehal.com");
        $u->setEPalsEmail("thiagoa_parent@mail.test.nehal.com");
        $u->setExternalEmail("thiagoa_parent@mac.com");
        $u->setUserId("2_812_1_1_4");
        $u->setFirstName("Nehal");
        $u->setLastName("Syed");
        $u->setPassword("Password123");
        
        
        $res = $u->add();
        print $res;
    }
    
    
     function addParentToSchool() 
    {
        //$u = new Parental("thiagoa_parent@test.nehal.com");
        //$u->addToSchool('test.nehal.com', 'School1');
        
         $u = new School();
         $u->addUserToSchool('thiagoa_parent@test.nehal.com', 'Student');
        
        $res = $u->add();
        print $res;
    }
    
    // not working
    function updateUser() {
        
        $u = new User("thiagoa_1@test.nehal.com");
        $u->setFirstName("Test FName");
        $u->setLastName("Test LName");
        
        $res = $u->update();
        print $res;
    }
    
     // not working
    function UserExists() {
        
        $res = User::userExists('thiagoa_1@test.nehal.com');
        printf($res);
        
        $res = User::userExists('matiasb@anytown.epals.com');
        printf($res);
        
    }
    
    // not working
    function VerifyPassword() {
        
        
        $u = new User("matiasb@anytown.epals.com");
        $result = $u->verifyPassword('Learning37');
        printf($result);
    }
    
     function loadUser() 
    {
        //$u = new Parental("thiagoa_parent@test.nehal.com");
        //$u->addToSchool('test.nehal.com', 'School1');
        
         $u = new User( );
        
        
        print $res;
    }
    
}

try
{
    
    $u = new User("sfocil239@sfocil1.epals.com");
    //$t->addUser(); 
    //$t->updateStudent();
   
     //$t->addStudent();
   
   
     //$t->addTeacher();
       //
       //$t->addParent();
     //$t->addParentToSchool();
     //$t->UserExists();
     //$t->VerifyPassword();
     
     
}
catch (Exception $e){
    echo $e;
    
}
?>
