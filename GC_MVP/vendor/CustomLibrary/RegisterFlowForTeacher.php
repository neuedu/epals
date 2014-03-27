<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//1.  TELL US ABOUT YOU
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


//2. TELL US ABOUT YOUR SCHOOL
// if school not exists, create, else load.
$school = new School();


//3.TELL US WHAT CLASSES YOU TEACH
// add class/course
$course = new Course();


//4.OPTIONAL
$up = new UserPreference("XXXX@epals.com"); 

$up->add("YearsExperience", XXXX); // XXXX comes from UI
$up->add("AcademicDegree", XXXX); // XXXX comes from UI
$up->add("MachingAvailability", XXXX); // XXXX comes from UI
