<?php

namespace Epals{
require_once('ApiEntityBroker.php');
require_once('EpalsEvent.php');

class EventBroker extends ApiEntityBroker {

    protected $endpoint_create = '/event';
    protected $endpoint_load = '/event';
    protected $endpoint_delete = '/event';
    protected $endpoint_update = '/event';

    function createBlankEntity() {
      return new EpalsEvent();
    }

 
}

}

