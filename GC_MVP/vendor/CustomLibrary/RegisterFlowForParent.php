<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//1.TELL US ABOUT YOU
//create parent

$parent = new Parental();


//2.TELL US ABOUT YOUR CHILDREN
// add CHILDREN

$student = new Student();


$parent->addStudent($student->getAccount());
