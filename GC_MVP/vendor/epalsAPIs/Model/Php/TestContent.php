<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestContent
 *
 * @author root
 */

require_once("Content.php");
$texts = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26);

foreach ($texts as $text) {
    $c = new Content();
    $c->id = "moo" . $text;
    $c->setAuthor("T.S Eliot");
    $c->setName("moo" . $text);
    $c->setUrl("/moo");
    $url = "http://www.bartleby.com/201/" . $text . ".html";
    $p = file_get_contents($url);
    $c->setData($p);
    $c->addMetadata("subject", "literature");
    $c->addMetadata("type", "poetry");
    $res = $c->add();
    print_r($res);
}
