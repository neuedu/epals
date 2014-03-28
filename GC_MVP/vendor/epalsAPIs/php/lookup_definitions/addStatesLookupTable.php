<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../../Model/Php");

require_once("Role.php"); // cmon php, get your act together
require_once("EpalsSql.php");

$l = new MongoDocument();

//drop old collection
$c = $l->m->selectCollection("gc", "states");
$c->drop();

$c = $l->m->selectCollection("gc", "states");

$s = new EpalsSql();
$res = $s->get_results("select * from country_states");

$m = array();
$m['us'] = array();
$m['ca'] = array();

foreach ($res as $r)
{
  $shortName = strtolower(preg_replace('/ /', '_', $r['state']));

  $a = array("shortName" => $shortName, "longName" => $r['state']);
  array_push($m[$r['country']], $a); 

}

$x = array('country' => 'ca', 'states' => $m['ca']);

$c->insert($x);

$x = array('country' => 'us', 'states' => $m['us']);

$c->insert($x);

