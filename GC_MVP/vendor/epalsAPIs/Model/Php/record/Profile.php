<?php

namespace TwoPhase
{
    
require_once("record/Record.php");

class Profile extends Record 
{
  function __construct($id = NULL) 
  {
    if (get_class() == "Record")
    {
      error_log("You should not instantiate this class, use ProfilePublic or ProfilePending instead");
      error_log(debug_backtrace());
      exit;
    }
    parent::__construct($id);
  }

  function insert($options = null)
  {
    $this->createDate = new \MongoDate(time());
    $this->last_modified = new \MongoDate(time());
    
    parent::insert($options);
  }

  function save($options = null)
  {
    parent::update($options);
  }

  function update($options = null)
  {
    $this->last_modified = new \MongoDate(time());

    parent::update($options);
  }

  function log_moderation_action($action)
  {
   $log = array();
   $log['action'] = $action;
   $log['time'] = new \MongoDate();
   if (isset($_SERVER['REMOTE_USER'])) $log['admin_username'] = $_SERVER['REMOTE_USER'];
   if (isset($this->data['moderation_log']))
   {
    array_push($this->data['moderation_log'], $log);
   } else {
    $this->data['moderation_log'] = array($log);
   }
  }

  function skip_solr_key($key)
  {
    if ($key == "clickthroughs") return true;
    if ($key == "moderation_log") return true;
    return false;
  }



}
}
