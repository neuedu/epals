<?php

namespace Epals {

require_once('ApiEntityBroker.php');

class LookupTable extends ApiEntityBroker {
   protected $data;

   public function __construct($session) {
      parent::__construct($session); 
      $this->load();
   }

   protected function load_url() {
       return $this->hostname . $this->endpoint_load . $this->queryString();
   }


   public function load() {
      $response = json_decode($this->curl->get($this->load_url())->body);
      if ($response->status == 'ok')
      {
         $this->data = (array)$response->result;
      } else {
         error_log("NOTICE: Could not load lookup table.");
         $this->data = array();
      }
   }

}

}
