<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../../Model/Php");

require_once("Role.php");

$l = new MongoDocument();

//drop old collection
$c = $l->m->selectCollection("gc", "schoolTypes");
$c->drop();

$c = $l->m->selectCollection("gc", "schoolTypes");

$a = array("shortName" => 'public', "longName" => 'Public');

$c->insert($a);

$a = array("shortName" => 'private', "longName" => 'Private');

$c->insert($a);

$a = array("shortName" => 'charter', "longName" => 'Charter');

$c->insert($a);

$a = array("shortName" => 'home', "longName" => 'Home');

$c->insert($a);

$a = array("shortName" => 'faithbased', "longName" => 'Faith-Based');

$c->insert($a);
$a = array("shortName" => 'international', "longName" => 'International');
$c->insert($a);
$a = array("shortName" => 'other', "longName" => 'Other');
$c->insert($a);

