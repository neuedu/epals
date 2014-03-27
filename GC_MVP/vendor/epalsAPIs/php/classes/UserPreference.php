<?php
namespace Epals {

require_once('ApiEntityKeyValue.php');

class UserPreference extends ApiEntityKeyValue
{
  protected $endpoint_create = '/preference';
  protected $endpoint_load = '/preference';
  protected $endpoint_load_many = '/preferences';
  protected $endpoint_delete = '/preference';
  protected $endpoint_update = '/preferences';

}
}
