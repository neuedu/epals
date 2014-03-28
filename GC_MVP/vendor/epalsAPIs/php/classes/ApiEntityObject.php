<?php

namespace Epals {

class ApiEntityObject {
  protected $data = array();
  protected $errors = array();

  function __construct($id = null)
  {
    if (!is_null($id)) { $this->setId($id); }
  }

  function setPrivateData($data) {
    $this->data = $data;
  }

  function getPrivateData() {
    return $this->data;
  }

  function getId() {
   return isset($this->data['id']) ? $this->data['id'] : null;
  }
  
  function setId($id) {
   $this->data['id'] = $id;
  }

  function addError($errorMessage)
  {
   if (is_array($errorMessage)) {
     foreach ($errorMessage as $e) {
      array_push($this->errors, $e->message);
     }
   } else {
      if (is_object($errorMessage)) {
        array_push($this->errors, $errorMessage->message);
      } else {
        array_push($this->errors, $errorMessage);
      }
   }
  }

  function errorString() {
    $s = implode("\n", $this->errors);
    return $s;
  }

  function toJSON() {
    return json_encode($this->data);
  }

}

}
