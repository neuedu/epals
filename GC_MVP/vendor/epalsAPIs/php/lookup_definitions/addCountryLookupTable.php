<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../../Model/Php");

require_once("Role.php");

$l = new MongoDocument();

//drop old collection
$c = $l->m->selectCollection("gc", "countries");
$c->drop();

$c = $l->m->selectCollection("gc", "countries");


$res = file('countries.txt');
foreach ($res as $r)
{
  $cl = preg_split("/\|/", $r);

  $a = array("shortName" => $cl[0], "longName" => trim($cl[1]));

  $c->insert($a);
}



