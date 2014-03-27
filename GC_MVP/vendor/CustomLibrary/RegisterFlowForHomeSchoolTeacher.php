<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// 1.TELL US ABOUT YOU  
// create teacher
$t = new Teacher();
$t->setFirstName("free"); //FirstName comes from UI
$t->setLastName("dragon"); //LastName comes from UI
$t->setGrade("Male");      //Grade comes from UI
$t->setExternalEmail("free@neusoft.com");    //Eamil comes from UI
$t->setUserId("ley");   //UserName comes from UI
$t->setPassword("123456");//password comes from UI
//Birthday not found in UI
//Your title not fount in UI
$t->setAccount("free@epals.com");    //Account not fount in UI
$t->setEPalsEmail("XXXX@mail.epals.com");  //EPalsEmail not fount in UI 

$t->add();

//2. TELL US ABOUT YOUR TEACHING ENVIRONMENT
// add preference
$up = new UserPreference("free@epals.com");
$up->add("ExtraRole", "HomeSchoolTeacher");
$up->add("ClosedTeachingEnvironment", true); // true/false comes from UI selection
$up->add("HomeSchoolAddress", "XXXX");  // // XXXX comes from UI


//3. TELL US WHAT YOU TEACH  
// add course
$course = new Course();





