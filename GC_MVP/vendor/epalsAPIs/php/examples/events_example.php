<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../classes");

require_once('EpalsEvent.php');
require_once('EventBroker.php');

$event_broker = new Epals\EventBroker();

// create a new event
$p = new Epals\EpalsEvent();
$p->setType('type');
$p->setData('data');
$p->setCallback('callback');

if ($event_broker->add($p) === FALSE)
{
   echo "something went wrong creating an event: " . $p->errorString() . "\n";
   exit;
}

echo "Added new event with id: " . $p->getId() . "\n";
sleep(1);

$e = $event_broker->load($p->getId());

// load an event
echo "event type: " . $e->getType() . "\n";
echo "event data: " . $e->getData() . "\n";
echo "event callback: " . $e->getCallback() . "\n";


// update an event
$e->setType('type2');
$e->setData('data2');
$e->setCallback('callback2');

if ($event_broker->update($e) === FALSE) {
  echo "something went wrong updating an event: " . $p->errorString() . "\n";
  exit;
}

echo "Updated event result : " . $e->getId() . "\n";

// delete this event

if ($event_broker->delete($e) === FALSE) {
  echo "something went wrong deleting an event: " . $p->errorString() . "\n";
  exit;
}

echo "event was deleted\n";

