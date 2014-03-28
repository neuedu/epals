<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../Model/Php");
set_include_path(get_include_path() . PATH_SEPARATOR . "classes/");

require 'classes/RestApiProfile.php';
require 'classes/RestApiUserPreference.php'; 
require 'classes/RestApiUserAttribute.php'; 
require 'classes/RestApiSession.php';
require 'classes/RestApiEvent.php'; 
require 'classes/RestApiLookup.php'; 

$loader = require 'vendor/autoload.php';

// too bad composer can't take care of this.  Epi should be rewritten to use namespaces and autoloading anyways
// but that really on saves us these two lines of code :)
$cm = $loader->getClassMap();
Epi::setPath('base', dirname($cm['Epi']));

Epi::init('api');

// this uses the new api ini loader that we added to create the api via ini file
Epi::setPath('config', 'classes/Config/');
getApi()->load('api_routes.ini');

getRoute()->run();


// the only function that needs to be here
function version()
{
  return '0.0.2';
}

