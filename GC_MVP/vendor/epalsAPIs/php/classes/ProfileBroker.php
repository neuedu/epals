<?php

namespace Epals {

require_once('ApiEntityBroker.php');

class ProfileBroker extends ApiEntityBroker {

    protected $endpoint_create = '/profile';
    protected $endpoint_load = '/profile';
    protected $endpoint_load_many = '/profiles';
    protected $endpoint_delete = '/profile';
    protected $endpoint_update = '/profile';
    
    protected $endpoint_approve = '/admin/profile/approve';
    protected $endpoint_hold = '/admin/profile/hold';

    /*static $PROFILE_STATUS_DRAFT = 6;
    static $PROFILE_STATUS_REJECTED = 4;
    static $PROFILE_STATUS_PENDING = 5;
    static $PROFILE_STATUS_DRAFT = 6;
    static $PROFILE_STATUS_AUTO_APPROVED = 7;*/

    function createBlankEntity() {
      return new Profile();
    }

    function objectToJSON($object)
    {
        $a = $object->getPrivateData();
        if (!is_null($this->onBehalfOf)) { $a['account'] = $this->onBehalfOf; }
        return json_encode($a);
    }

    function hold_url($id)
    {
        return $this->hostname . $this->endpoint_hold . '/' . $id . $this->queryString();
    }
    
    function approve_url($id)
    {
        return $this->hostname . $this->endpoint_approve . '/' . $id . $this->queryString();
    }
    
    function approve($profile)
    {
        $request_object = $profile->toJSON();
        $x = $this->curl->put($this->approve_url($profile->getId()), $request_object)->body;
        $response = json_decode($x);
        if ($response->status == 'ok')
        {
           $profile->setPrivateData((array)$response->result);
           $res = TRUE;
        } else {
           $profile->addError($response->error);
           $res = FALSE;
        }
        return $res;
    }
    
    function hold($profile, $holding_comments)
    {
        $request_object = json_encode(array('holding_comments' => $holding_comments));
        $response = json_decode($this->curl->put($this->hold_url($profile->getId()), $request_object)->body);
        if ($response->status == 'ok')
        {
           $profile->setId($response->result->id);
           $res = TRUE;
        } else {
           $profile->addError($response->error);
           $res = FALSE;
        }

        return $res;
    }

    function load_by_username($username)
    {
        $response = json_decode($this->curl->get($this->load_url($username))->body);
        $res = array();
        if ($response->status == 'ok')
        {
          foreach ($response->result as $r)
          {
            $p = $this->createBlankEntity();
            $p->setPrivateData((array)$r);
            array_push($res, $p);
          }
        } else {
          $this->error = $response->error;
        }
        return $res;
    }


    function load_url($id) {
        if (preg_match('/\@/', $id)) {
            $endpoint = $this->endpoint_load_many;
        } else {
            $endpoint = $this->endpoint_load;
        }

        return $this->hostname . $endpoint . '/' . $id . $this->queryString();
    }

}
}
