<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../classes");

require_once('Profile.php');
require_once('ProfileBroker.php');
require_once('Session.php');
require_once('SessionBroker.php');

$session_broker = new Epals\SessionBroker();
$teacher_session = $session_broker->login('steve@epals.com', 'password');
if ($teacher_session->isAuthenticated() === FALSE)
{
  echo "Could not login to teacher account: " . $teacher_session->errorString() . "\n";
  exit;
}
sleep(1);

$profile_broker = new Epals\ProfileBroker($teacher_session);

// create a profile
$p = new Epals\Profile();
$p->setSchoolName('My School'); // required field!
$p->setDescription('I like to make this api and I am going to finish this sentence without any apostrophies');
$p->setEmail('steve@epals.com');

if ($profile_broker->add($p) === TRUE) {
 echo "Profile was added for steve@epals.com!  ID: " . $p->getId() . " \n";
 $created_id = $p->getId();
} else {
 echo "Something went wrong while adding a profile\n";
 var_dump($p);
 exit;
}

sleep(1);

// load a profile
$p = $profile_broker->load($created_id);
echo "Id of loaded profile: " . $p->getId() . "\n";
echo "DeSC: " . $p->getDescription() . "\n";
echo "email: " . $p->getEmail() . "\n";

// load all profiles (current max is 10 pending + 10 approved)
// print the id of the first result
$response = $profile_broker->load_by_username('steve@epals.com');
echo "Loaded all profiles for steve@epals.com, id of first one: " . $response[0]->getId() . "\n";

//approve the profile
// need an admin session
$admin_session = $session_broker->login('admindemo@epals.com', 'adminwest');
if ($admin_session->isAuthenticated() === FALSE)
{
  echo "Could not login to admin account: " . $teacher_session->errorString() . "\n";
  exit;
}
sleep(1); 

$admin_broker = new Epals\ProfileBroker($admin_session);

$new_profile = new Epals\Profile($created_id);

if ($admin_broker->approve($new_profile) === TRUE) 
{
  echo "approved profile id: " . $new_profile->getId() . "\n";
  $created_id = $new_profile->getId();
} else {
  echo "Something went wrong approving a profile\n";
  exit;
}

// load a list of all profiles the user has
$response = $admin_broker->load_by_username('steve@epals.com');
echo "First id from load list of profiles: " . $response[0]->getId() . "\n";

// update
$p = new Epals\Profile($created_id);
$p->setDescription('This is the new description');
$p->setEmail('newemail@epals.com');
if ($admin_broker->update($p) === TRUE) {
 $updated_id = $p->getId();
 echo "Profile was updated for steve@epals.com!  ID: " . $p->getId() . " \n";
} else {
 echo "Something went wrong while updating a profile\n";
 exit;
}

//place profile on hold
if ($admin_broker->hold($new_profile, 'This profile does not contain enough information.') === TRUE)
{
 echo "profile id on hold: " . $new_profile->getId() . "\n";
} else {
 echo "Something went wrong placing the profile on hold\n";
 exit;
}

$admin_broker->onBehalfOf('steve@epals.com'); // for admins to create profiles on behalf of other users
$p = new Epals\Profile();
$p->setDescription('This is the new description on behalf of steve');
$p->setSchoolName('OnBehalfOf');
$p->setEmail('steve@epals.com');
if ($admin_broker->add($p) === TRUE) {
 echo "profile was created on behalf of steve@epals.com by an admin: " . $p->getId() . "\n";
} else {
 echo "something went wrong creating a profile on behalf of a user: " . $p->errorString() . "\n";
 exit;
}

// delete
if ($admin_broker->delete($new_profile) === TRUE)
{
  echo "Final test ok, Profile was deleted!\n";
} else {
  echo "Something went wrong deleting the profile\n";
  exit;
}

echo "All examples completed\n\n";

exit;

