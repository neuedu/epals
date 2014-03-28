<?php

namespace Epals {

require_once('ApiEntityKeyValueBroker.php');

class UserAttributeBroker extends ApiEntityKeyValueBroker
{
  protected $endpoint_create = '/attribute';
  protected $endpoint_load = '/attribute';
  protected $endpoint_load_many = '/attributes';
  protected $endpoint_delete = '/attribute';
  protected $endpoint_update = '/attributes';



}
}
