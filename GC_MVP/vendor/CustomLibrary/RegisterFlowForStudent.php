<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("/home/www/GC_MVP/vendor/epalsAPIs/php/school.php");
require_once("/home/www/GC_MVP/vendor/epalsAPIs/php/student.php");
require_once("/home/www/GC_MVP/vendor/epalsAPIs/php/UserPreference.php");

//1.TELL US ABOUT YOU
//add student

$student = new Student();
$student->setFirstName($firstName);
$student->setLastName($lastName);
//if age<=13 send email to parent

//else age>13

$student->setExternalEmail($externalEmail);
$student->setGrade($grade);



//2.TELL US ABOUT YOUR SCHOOL
//if school not exists,create, else load.
$school = new School();

        
// add student to school
$school->addUserToSchool($student->getAccount(), $student->getRoles());


//3.MORE ABOUT YOU
$up = new UserPreference();








