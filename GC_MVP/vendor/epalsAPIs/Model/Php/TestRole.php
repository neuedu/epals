<?php

require_once("Role.php");

$r = new Role("student");
$r->add();
$t = new Role("teacher");
$t->add();
$moo = $r->getpRole();
print_r($moo);

?>