<?php

namespace TwoPhase
{

require_once("record/Record.php");
require_once("record/Profile.php");
require_once("record/ProfileDeleted.php");
require_once("ConfigProfiles.php");

class ProfilePending extends Profile 
{
  function get_collection_name()
  {
    return PENDING_PUBLIC_PROFILES_COLLECTION;
  }

  function init_solr()
  {
    // noop - pending profiles dont touch solr
  }
  function update_solr()
  {
    // noop - pending profiles dont touch solr
  }
  function insert_solr()
  {
    // noop - pending profiles dont touch solr
  }
  function delete_solr()
  {
    // noop - pending profiles dont touch solr
  }

  function insert($options = null, $auto_approve = FALSE)
  {
    parent::insert($options);
    //$auto_approve = FALSE;
    if ($auto_approve == TRUE)
    {
      $this->log_moderation_action(MODERATION_ACTION_AUTO_APPROVE);
      $ap = new ProfilePublic();
      
      // create a new approved profile to match the pending profile

      $d = $this->get_data_copy();
      $d['approved_date'] = new \MongoDate(time());
      $d['auto_approved_pending_id'] = $this->id;
      unset($d['approved_profile_id']);
      unset($d['status']);
      unset($d['id']); 

      $ap->set_data($d);
      $ap->insert();

      $this->status = PROFILE_STATUS_AUTO_APPROVED; 
      $this->auto_approved_id = $ap->id;
      $this->update();
    }
  }

  function is_modified()
  {
    return isset($this->approved_profile_id);
  }

  // delete current profile from all storage and place in deleted collection
  function delete()
  {
    $p = new ProfileDeleted();
    $this->log_moderation_action(MODERATION_ACTION_DELETE);
    $d = $this->get_data_copy();
    if (isset($d['auto_approved_id']))
    {
      $pp = new ProfilePublic($d['auto_approved_id']);
      if ($pp->user_id > 0)
      {
        $pp->_delete();
      }
    }
    $d['old_id'] = $d['id'];
    unset($d['id']);
    $p->set_data($d);
    $p->insert();
    parent::delete();
  }

  function approve()
  {
    if (isset($this->approved_profile_id))
    {
      $this->log_moderation_action(MODERATION_ACTION_APPROVE_MODIFICATION);
    } else {
      $this->log_moderation_action(MODERATION_ACTION_APPROVE);
    }

    // if we have an approved_profile_id, delete the old one
    $d = $this->get_data_copy();
    $d['approved_date'] = new \MongoDate(time());
    unset($d['approved_profile_id']);
    unset($d['auto_approved_id']);
    unset($d['status']);
    if (isset($d['collaboration']) && !(is_array($d['collaboration']))) unset($d['collaboration']);
    if (isset($d['languages']) && !(is_array($d['languages']))) unset($d['languages']);
    if (isset($d['subjects']) && !(is_array($d['subjects']))) unset($d['subjects']);
    unset($d['id']);  // need a copy of the array instead, deleting it here deletes the id from the original
    $stored = false;
    if (isset($this->approved_profile_id))
    {
      $p = new ProfilePublic($this->approved_profile_id);
      if (count($p->get_data()) > 0)
      {
       $stored = true; 
       $p->set_data_copy($d);
       $p->update();
       $p->updateMongoByQuery(array('id' => $p->id), array('$unset' => array('age_display_s' => "")));
       $this->transformed = $p->get_data_copy();
      } 
    } 
    else if (isset($this->auto_approved_id))
    {
      $p = new ProfilePublic($this->auto_approved_id);
       if (count($p->get_data()) > 0)
       {
         $stored = true;
         $p->set_data_copy($d);
         $p->update();
         $p->updateMongoByQuery(array('id' => $p->id), array('$unset' => array('age_display_s' => "")));
         $this->transformed = $p->get_data_copy();
       }
    }
    if (!$stored)
    {
      $p = new ProfilePublic();
      $p->set_data($d);
      $p->insert();
      $this->transformed = $p->get_data_copy();
    }
    parent::delete();
  }

  function hold($holding_comments)
  {
    $this->log_moderation_action(MODERATION_ACTION_HOLD);

    if (isset($this->auto_approved_id))
    {
      $p = new ProfilePublic($this->auto_approved_id);
      $p->_delete();
    }
    $this->holding_comments = $holding_comments;
    $this->status = PROFILE_STATUS_REJECTED;
    $this->update();
    $this->transformed = $this->get_data_copy();
  }
}
}
