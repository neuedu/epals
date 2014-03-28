<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../classes");

require_once('Session.php');
require_once('SessionBroker.php');
require_once('UserAttribute.php');
require_once('UserAttributeBroker.php');

$session_broker = new Epals\SessionBroker();
$teacher_session = $session_broker->login('steve@epals.com', 'password');

$user_attribute_broker = new Epals\UserAttributeBroker($teacher_session);

// set an attribute
$p = new Epals\UserAttribute();
$p->set('cool', true);

if ($user_attribute_broker->update($p) === FALSE)  // add and update are essentially aliases in this broker
{
  echo "Something went wrong setting an attribte: " . $user_attribute_broker->errorString() . "\n";
  exit;
}

echo "attribute was added, steve is now cool\n";

// load attributes
$attributes = $attribute_broker->load();
$keys = $attributes->getKeys();

foreach ($keys as $k)
{
    echo "Loaded attribute from list $k : " . $attributes->get($k) . "\n";
}

//clear attribute
if ($user_attribute_broker->delete('cool') === FALSE)
{
  echo "Something went wrong setting an attribte: " . $user_attribute_broker->errorString() . "\n";
  exit;
}

echo "attribute was cleared, steve is no longer cool\n";

