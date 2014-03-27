<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestPreference
 *
 * @author shaz
 */

require_once("UserPreference.php");

$up = new UserPreference("glinky");
//$up->delete();
//var_dump($up);
/*
$res = $up->add("Email","No");
$res = $up->add("Something","No");
$res = $up->add("Mice","No");
$res = $up->add("Email","Yes");

$res = $up->add("sun", 5);
 * 
 */
//$res = $up->getByKey("bum", 5);
//$res = $up->getAll();
var_dump($up->policy);
$acl->addRole("goofy");
$up->update();
?>
