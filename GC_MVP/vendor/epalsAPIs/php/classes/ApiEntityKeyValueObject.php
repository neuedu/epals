<?php

namespace Epals {

class ApiEntityKeyValueObject extends ApiEntityObject {

public function __construct() {
}

public function set($key, $value) {
  $this->data[$key] = $value;
}


}

}
