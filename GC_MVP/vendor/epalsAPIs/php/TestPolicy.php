<?php

require_once("Policy.php");
$p = new Policy("myname");

$res = $p->save();
$s = new Role("student");
$t = new Role("teacher");
$m = new Role("moderator");

$dork = new Role("dingadonga");

$pk = new Role("pakistani");

$p->deny($s, "pork");
$p->allow($t, "dork");
$p->deny($s, "meow");
$p->allow($m, "meow");
$p->deny($dork, "woof");
$p->allow($pk, "woof");

$saved = $p->save();

//print_r($p);

$res = $p->isAllowed("teacher", "dork");
print("teacher allowed  dork? " . (int)$res . "\n\n");

$res = $p->isAllowed("student", "meow");
print("student allowed meow? " . (int)$res . "\n\n");

$res = $p->isAllowed("moderator", "meow");
print("moderator allowed meow? " . (int)$res . "\n\n");

$res = $p->isAllowed("dingadonga", "woof");
print("dingadonga allowed woof? " . (int)$res . "\n\n");


$res = $p->isAllowed("pakistani", "pork");
print("Pakistani allowed  pork? " . (int)$res . "\n\n");

?>
