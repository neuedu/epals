<?php

namespace TwoPhase
{

require_once("record/Record.php");
require_once("record/Profile.php");
require_once("ConfigProfiles.php");

class ProfileDeleted extends Profile 
{
  function get_collection_name()
  {
    return DELETED_PROFILES_COLLECTION;
  }

  function init_solr()
  {
    // noop - deleted profiles dont touch solr
  }
  function update_solr()
  {
    // noop - deleted profiles dont touch solr
  }
  function insert_solr()
  {
    // noop - deleted profiles dont touch solr
  }
  function delete_solr()
  {
    // noop - deleted profiles dont touch solr
  }

  function restore()
  {
    // if we have an approved_profile_id, delete the old one
    $d = $this->get_data();
    $d['status'] = PROFILE_STATUS_DRAFT;
    unset($d['id']);

    $p = new ProfilePending();
    $p->set_data($d);
    $p->log_moderation_action(MODERATION_ACTION_RESTORE);
    $p->insert();
    
    $this->delete();
  }
}
}