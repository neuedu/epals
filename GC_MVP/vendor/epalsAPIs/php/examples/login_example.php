<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../classes");

require_once('SessionBroker.php');
require_once('Session.php');

//create a SessionBroker that will give us the ability to create new sessions
$session_broker = new Epals\SessionBroker();

// ask for a session to be created
$teacher_session = $session_broker->login('steve@epals.com', 'password');
if ($teacher_session->isAuthenticated() === FALSE)
{
  echo "Could not login to teacher account: " . $teacher_session->errorString() . "\n";
  exit;
}
sleep(1);

echo "User logged in, session id: " . $teacher_session->getId() . "\n";

// Log out the user
if ($session_broker->logout($teacher_session) === FALSE)
{
  echo "Could not logot of teacher account: " . $teacher_session->errorString() . "\n";
}

echo "User has logged out\n";


