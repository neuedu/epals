<?php

require_once("Document.php");

class Response extends Document {
    public $thread;
    public $text;
    public $author;
    public $votes;
    public $files;
    public $links;
    
    function __construct() {
        parent::__construct();
        $this->collection = $this->m->selectCollection("gc", "response");
        $this->saveSOLR = FALSE;
    }
    
    function add() {
        parent::add();
    }
    
    function fetchByThread($thread) {
        $result = parent::fetch("thread", thread);
        return $result;
    }
    
    function fetchByAuthor($author) {
        $result = parent::fetch("author", $author);
        return $result;
    }

}
?>
