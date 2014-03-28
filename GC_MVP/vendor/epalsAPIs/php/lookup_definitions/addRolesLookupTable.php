<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../../Model/Php");

require_once("Role.php");

$l = new MongoDocument();

//drop old collection
$c = $l->m->selectCollection("gc", "roles");
$c->drop();

$c = $l->m->selectCollection("gc", "roles");

$a = array("shortName" => 'teacher', "longName" => 'Teacher');

$c->insert($a);

$a = array("shortName" => 'teacher_homeschool', "longName" => 'Home-School Teacher');

$c->insert($a);

$a = array("shortName" => 'student', "longName" => 'Student');

$c->insert($a);

$a = array("shortName" => 'parent', "longName" => 'Parent / Guardian');

$c->insert($a);

$a = array("shortName" => 'mentor', "longName" => 'Mentor');

$c->insert($a);

