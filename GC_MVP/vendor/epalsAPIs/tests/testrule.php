<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestRule
 *
 * @author nehalsyed
 */

require_once("../php/rule.php");
class TestRule {
    //put your code here
    
    function addRule() {
        
        $u = new Rule();
        $u->setRulename("test rule");
        $u->setDescription("test desc");
        $u->setEnabled(true);
        
        $res = $u->saveRule();
        print $res;
    }
    
}

try{

    $t = new TestRule();
    $t->addRule();
}
catch (Exception $e){
    echo $e;
    
}
?>
