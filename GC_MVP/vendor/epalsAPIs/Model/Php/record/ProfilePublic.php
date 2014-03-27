<?php

namespace TwoPhase
{

require_once("record/Record.php");
require_once("record/Profile.php");
require_once("ConfigProfiles.php");

class ProfilePublic extends Profile 
{
  function get_collection_name()
  {
    return PUBLIC_PROFILES_COLLECTION;
  }

  function isVisibleToUser($user_id, $district_id = 0, $users_in_school = array())
  {
    // profiles are visible to owner at all times
    if (intval($user_id) > 0)
    {
     if ($this->user_id == $user_id)
     {
      return TRUE;
     }
    }

    // check for hidden profiles
    if ($this->visibility == PROFILE_VISIBILITY_HIDDEN)
    {
      return FALSE;
    }

    // is profile visible to anyone in the school?
    if ($this->visibility == PROFILE_VISIBILITY_SCHOOLS)
    {
       if (in_array($this->user_id, $users_in_school))
       {
         return TRUE;
       }
       return FALSE;
    }

    if ($this->visibility == PROFILE_VISIBILITY_DISTRICT)
    {
       if ($this->district_id == $district)
       {
         return TRUE;
       }
       return FALSE;
    }

    if ($this->visibility == PROFILE_VISIBILITY_SCHOOLMAIL)
    {
       if ($district > 0)
       {
         return TRUE;
       }
       return FALSE;
    }

    if ($this->visibility == PROFILE_VISIBILITY_PUBLIC)
    {
       return TRUE;
    }

    error_log("Unknown visibility status : " . $this->visibility);
    error_log("This needs to a valid PROFILE_VISIBILITY_* before we can display it" . $this->visibility);
    error_log(debug_backtrace());
    exit;
  }

  function hold($holding_comments)
  {
    $this->log_moderation_action(MODERATION_ACTION_HOLD);

    $this->holding_comments = $holding_comments;
    $this->status = PROFILE_STATUS_REJECTED;
    $d = $this->get_data();
    unset($d['id']);

    if (isset($d['auto_approved_pending_id']))
    { // actioning an approved profile because it has the pending id in it
      $p = new ProfilePending($d['auto_approved_pending_id']);
      if (is_null($p->id))
      {
        error_log("the auto_approved_pending was missing, moving the profile to pending collection");
        unset($d['auto_approved_pending_id']);
        $p = new ProfilePending(); // if ther was no auto_approved_profile, 
        $p->set_data($d);
        $p->insert();
        $this->delete();
        $this->transformed = $p->get_data_copy();
      } else {
       $p->holding_comments = $holding_comments;
       $p->status = PROFILE_STATUS_REJECTED;
       unset($p->approved_profile_id);
       error_log("About to call update on hold for pending profile");
       $p->update();
       error_log("About to delete this profile");
       $this->_delete();
       $this->transformed = $p->get_data_copy();
      }
    } else { // actioning approved item... it has not auto_approve link
      $p = new ProfilePending();
      $p->set_data($d);
      $p->insert();
      $this->delete();
      $this->transformed = $p->get_data_copy();
    }
  }

  function clickthrough($position, $keyword, $ids)
  {
    $a = array('position'=>$position, 'keyword'=>$keyword, 'ids' => $ids, 'date_added' => new MongoDate());
    if (isset($this->clickthroughs))
    {
      error_log("OLD CLICKTHROUGH UPDATED");
      array_push($this->data['clickthroughs'], $a); 
    } else {
      error_log("NEW CLICKTHROUGH ADDED");
      $this->clickthroughs = array($a);
    }
    $this->update_mongo();
    $this->delete_memcache($this->id);
  }

  function before_save_solr($doc)
  {
    if (isset($this->data['description']))
    {
      $doc->addField('description_length', strlen($this->data['description']));
    }
  }

  function delete()
  {
    // if a pending profile has 'approved_profile_id' matching this one,
    // we need to clear out that field

    $p = new ProfilePending();
    error_log("To delete, searching for pending with app id of " . $this->id);
    $p->updateMongoByQuery(array('approved_profile_id' => $this->id), array('$unset' => array('approved_profile_id' => 1)));

    $p = new ProfileDeleted();
    $this->log_moderation_action(MODERATION_ACTION_DELETE);
    $d = $this->get_data_copy();

    if (isset($d['auto_approved_pending_id']))
    {
      $pp = new ProfilePending($d['auto_approved_pending_id']);
      if ($pp->user_id > 0)
      {
        $pp->_delete();
      }
    }
    $d['old_id'] = $d['id'];
    unset($d['id']);
    $p->set_data($d);
    error_log("about to insert into deleted collection");
    $p->insert();
    error_log("about to do the rest of the delete");
    parent::delete();

    
    parent::delete();
  }
}
}