<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../Model/Php");

require_once('EpalsPassword.php');
require_once('user.php');
require_once('EpalsSql.php');

$a = preg_split('/\@/', $argv[1]);

$u = $a[0];
if (!isset($a[0])) { echo "Must specify epals username on command line\n"; exit; }
if (isset($a[1])) { $d = $a[1]; } else { $d = 'epals.com'; }

$s = new EpalsSql();

$id = intval($s->get("select id from nameSpaces where name='" . mysql_real_escape_string($d) . "'"));

$x = "select * from users where user='" . mysql_real_escape_string($u) . "' and domain='" . mysql_real_escape_string($id) . "'";
echo "$x\n";
$res = $s->get_results($x);

if (count($res) == 0)
{
  echo "Could not find that user in mysql\n";
  exit;
}

$p = new EpalsPassword($res[0]['salt']);
$a = array($res[0]['role']);

$o = new User();
$o->setPassword($p->decrypt($res[0]['pass']));
$o->setRoles($a);
$o->setAccount($u . '@' . $d);
$o->setEPalsEmail($u . '@' . $d);
$o->setExternalEmail($u . '@' . $d);
$o->setFirstName($res[0]['fname']);
$o->setLastName($res[0]['lname']);
$o->add();

