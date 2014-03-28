<?php

require_once("Event.php");

foreach (range(1,60) as $number) {
    $e = new Event("log", "my new data " . $number, "function() {}");
    $res = $e->add();
    var_dump($res);
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>