<?php

require_once("Record.php");
require_once("Response.php");

class Thread extends Record {
    public $name;
    public $text;
    public $author;
    public $category;
    public $files;
    public $links;
    public $metadata;
    

    function __construct($id = NULL) {
        parent::__construct();
        $this->collection = $this->m->selectCollection("gc", "topic");
        if ($id) {
            $this->fetchByID($id);
        }
    }
    
    function add() {
        if (is_null($this->votes)) {
            $this->votes = 0;
        }
        parent::add();
    }
    
    function fetchByID($id) {
        $result = parent::fetchOne("id", $id);
    }
    
    function fetchBycategory($categoryID) {
        $result = parent::fetch("category", $categoryID);
        return $result;
    }
    
    function fetchByAuthor($author) {
        $result = parent::fetch("author", $author);
        return $result;
    }
    
    function getResponses($id=NULL) {
        $r = new Response();
        if ($id) {
            $result = $r->fetch("topic", $id);
        } else {
            $result = $r->fetch("topic", $this->id);
        }
        return $result;
    }
    
    function upVote($id) {
        $vote = array('$inc'=>array('votes'=> 1));
        $this->collection->update(array('id' => $id), $vote);
    }

    function addView($id) {
        $new = array('$inc'=>array('views'=> 1));
        $this->collection->update(array('id' => $id), $new);
    }
}
?>
