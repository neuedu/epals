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

require_once("UserAttribute.php");

$up = new UserAttribute("sabrina");
var_dump($up);

$res = $up->add("Email","No");
$res = $up->add("Something","No");
$res = $up->add("Mice","No");
$res = $up->add("IceCream","Yes");

$res = $up->add("sun", 5);
 
$res = $up->getAll();
var_dump($res);

$res = $up->add("Email","yes");




//$res = $up->getByKey("bum", 5);
$res = $up->getAll();
var_dump($res);
$up->update();
?>
