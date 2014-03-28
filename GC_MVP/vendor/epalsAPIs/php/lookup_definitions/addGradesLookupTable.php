<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../../Model/Php");

require_once("Role.php");

$l = new MongoDocument();

//drop old collection
$c = $l->m->selectCollection("gc", "grades");
$c->drop();

$c = $l->m->selectCollection("gc", "grades");

$a = array("shortName" => 'prek', "longName" => 'Pre-K');

$c->insert($a);

$a = array("shortName" => 'kindergarten', "longName" => 'Kindergarten');

$c->insert($a);

$a = array("shortName" => 'first', "longName" => 'First');

$c->insert($a);

$a = array("shortName" => 'second', "longName" => 'Second');

$c->insert($a);

$a = array("shortName" => 'third', "longName" => 'Third');

$c->insert($a);
$a = array("shortName" => 'forth', "longName" => 'Forth');
$c->insert($a);
$a = array("shortName" => 'fifth', "longName" => 'Fifth');
$c->insert($a);
$a = array("shortName" => 'sixth', "longName" => 'Sixth');
$c->insert($a);
$a = array("shortName" => 'seventh', "longName" => 'Seventh');
$c->insert($a);
$a = array("shortName" => 'eigth', "longName" => 'Eighth');
$c->insert($a);
$a = array("shortName" => 'freshman', "longName" => 'Freshman');
$c->insert($a);
$a = array("shortName" => 'sophomore', "longName" => 'Sophomore');
$c->insert($a);
$a = array("shortName" => 'junior', "longName" => 'Junior');
$c->insert($a);
$a = array("shortName" => 'senior', "longName" => 'Senior');
$c->insert($a);

