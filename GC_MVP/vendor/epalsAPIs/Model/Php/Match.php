<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * The Match class. Matches are collaborations between users 
 *
 * @author shaz
 */

require_once("Document.php");
require_once("Thread.php");

class Match extends Document {
    protected $to;          // This is an array. 
    protected $from;
    protected $state;
    protected $workspace;
    protected $createTime;
    
    const INITIATED = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;
    const IN_DISCUSSION = 4;
    const COLLABORATING = 5;
    const ARCHIVED = 6;

    function __construct($id = null) {
        $this->saveSOLR = FALSE;
        parent::__construct();
        if (!(is_null($id))) {
            $this->fetchByID($id);
        } else {
            $this->createTime = time();
        }
    }
    
    function accept() {
        $this->state = self::ACCEPTED;
        $t = new Thread();
        $t->author = $to;
        $t->name = "Collaboration discussion between $to and $from";
        $t->add();
    }
    
    function reject() {
        $this->state = self::REJECTED;
    }
    
    function getState() {
        return $this->state;
    }
    
    function archive() {
        $this->state = self::ARCHIVED;
    }
    
    function setStateInDiscussion() {
        $this->state = self::IN_DISCUSSION;
    }
    
    function setStateInCollaboration() {
        $this->state = self::COLLABORATING;
    }
    
}

?>
