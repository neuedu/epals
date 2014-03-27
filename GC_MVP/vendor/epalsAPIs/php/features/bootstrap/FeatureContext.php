<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

set_include_path(get_include_path() . PATH_SEPARATOR . "./classes");

require_once('Profile.php');
require_once('Session.php');
require_once('SessionBroker.php');
require_once("Policy.php");
require_once("community.php");
require "user.php";
require "teacher.php";
require "student.php";
require "UserAttribute.php";
require "UserPreference.php";
require "Content.php";
require "Event.php";
require "teachersgroup.php";
require "CountryLookup.php";
require 'SchoolTypeLookup.php';
require 'AgeRangeLookup.php';
require 'GradeLookup.php';
require 'RoleLookup.php';
/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    private $tenant;
    private $user;
    private $teacher;
    private $parent;
    private $student;
    private $profile;
    private $domain;
    private $group;
    private $school;
    private $section;
    private $course;
    private $event;
    private $content;
    private $ua;
    private $up;
    private $randUserId;
    private $randTenantId;
    private $randSchoolId;
    private $message;
    private $session;
    private $session_broker;
    private $login;
    private $profile_created_id;
    private $tenant_name;
    private $policy;
    private $role;
    private $community;
    private $country_lookup;
    private $schooltype_lookup;
    private $agerange_lookup;
    private $grade_lookup;
    private $last_created_content_id;
    
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @Given /^I create new user$/
     */
    public function createNewUser()
    {
        $this->user = new User();
        assertNotNull($this->user);
    }
    
    /**
     * @Given /^I create user "([^"]*)" for tenant "([^"]*)"$/
     */
    public function createAndAddNewUserToTenant($username, $domain)
    {
        $this->user = new User();
        $this->randUserId = rand(0,1000);
        $this->user->setAccount("$username$this->randUserId@$domain");
        $this->user->setEPalsEmail("$username$this->randUserId@mail.$domain");
        $this->user->setExternalEmail("$username$this->randUserId@mac.com");
        $this->user->setUserId("2_810_1_1_10");
        $this->user->setFirstName("$username$this->randUserId");
        $this->user->setLastName("$username$this->randUserId");
        $this->user->setPassword("Password123");
        $this->user->setRawDob("19800101");
        $this->user->setGrade("11");
        $r = array("Student");
        $this->user->setRoles($r);
        $this->tenant_name = $domain;
    }
    
    /**
     * @Given /^I add user$/
     */
    public function addUser()
    {
        try{
            $this->user->add();
        } catch (Exception $ex) {
            $this->message = $ex->getMessage();
        }
    }
    
    /**
     * @Given /^user "([^"]*)" is in tenant "([^"]*)"$/
     */
    public function assertExistentUser($username,$domain)
    {
        assertTrue(user::userExists("$username$this->randUserId@$domain"));
    }
    
    /**
     * @Given /^user "([^"]*)" is not in tenant "([^"]*)"$/
     */
    public function assertInexistentUser($username,$domain)
    {
        assertFalse(user::userExists("$username$this->randUserId@$domain"));
    }
    
    /**
     * @Given /^user password is "([^"]*)"$/
     */
    public function assertMatchedPassword($password)
    {
        assertTrue($this->user->verifyPassword($password));
    }
    
    /**
     * @Given /^user password is not "([^"]*)"$/
     */
    public function assertMismatchedPassword($password)
    {
        assertFalse($this->user->verifyPassword($password));
    }
    
    /**
     * @Given /^I load user "([^"]*)" from tenant "([^"]*)"$/
     */
    public function loadUserInfo($username,$tenant)
    {
        $this->user = new User();
        $this->user->loadUser("$username$this->randUserId@$tenant");
    }
    
    /**
     * @Given /^I update password "([^"]*)"$/
     */
    public function updatePassword($password)
    {
        if($password = "")
            assertEquals("Failure",$this->user->updatePassword($password));
        else
            assertEquals("Success", $this->user->updatePassword($password));
    }   
    
    /**
     * @Given /^I set user data:$/
     */
    public function setUserData(TableNode $usersTable)
    {
        foreach ($usersTable->getHash() as $userHash){
            $this->user->setAccount($userHash['username']."@".$userHash['domain']);
            $this->user->setEPalsEmail($userHash['ePalsEmail']);
            $this->user->setExternalEmail($userHash['externalEmail']);
            $this->user->setUserId($userHash['userId']);
            $this->user->setFirstName($userHash['firstName']);
            $this->user->setLastName($userHash['lastName']);
            $this->user->setPassword($userHash['password']);
            $this->user->setRawDob($userHash['rawDob']);
            $this->user->setGrade($userHash['grade']);
            $r = array($userHash['role']);
            $this->user->setRoles($r);
        }
    }
    
    /**
     * @Given /^I set user info: "([^"]*)" "([^"]*)" "([^"]*)" "([^"]*)" "([^"]*)" "([^"]*)" "([^"]*)" "([^"]*)" "([^"]*)" "([^"]*)" "([^"]*)"$/
     */
    public function setUserInfo($username, $domain, $ePalsEmail, $externalEmail, $userId, $firstName, $lastName, $password, $rawDob, $grade, $role)
    {
            $this->randUserId = rand(0,1000);
            if($username == "" OR $externalEmail == "")
            {
                $this->randUserId = "";
            }
            
            $this->user->setAccount($username.$this->randUserId."@".$domain);
            $this->user->setEPalsEmail($ePalsEmail);
            $this->user->setExternalEmail($this->randUserId.$externalEmail);
            $this->user->setUserId($userId);
            $this->user->setFirstName($firstName);
            $this->user->setLastName($lastName);
            $this->user->setPassword($password);
            $this->user->setRawDob($rawDob);
            $this->user->setGrade($grade);
            $r = array($role);
            $this->user->setRoles($r);
    }
    
    /**
     * @Given /^I update user$/
     */
    public function updateUser()
    {
        try{
            $this->user->update();
        } catch (Exception $ex) {
            $this->message = $ex->getMessage();
        }
    }
    
    /**
     * @Given /^I cant add user again$/
     */
    public function assertFailAddUserAgain()
    {
        assertEquals("Cannot create a new User with ExternalId :".$this->user->getAccount()." because one already exists.",$this->message);
    }
    
    //Steps for Tenant feature
    
    /**
     * @Given /^I create and add test tenant$/
     */
    public function createTestTenant()
    {
        $tenant = new Tenant();
        $this->domain = "sfocil1.epals.com";
        $tenant->setDomain($this->domain);
        $tenant->setEmailDomain("mail.$this->domain");
        $tenant->setPublished('false');
        $tenant->setName("Test Global Community $this->domain");
        assertNotNull($tenant->add());
    }
    
    /**
     * @Given /^I create new tenant "([^"]*)"$/
     */
    public function createNewTenant($domain)
    {
        $this->tenant = new Tenant();
        $this->randTenantId = rand(0,1000);
        $this->domain = "$domain$this->randTenantId.epals.com";
        $this->tenant->setDomain("$this->domain");
        $this->tenant->setEmailDomain("mail.$this->domain");
        $this->tenant->setPublished('false');
        $this->tenant->setName("Test Global Community $this->domain");
    }
    
    /**
     * @Given /^I add tenant$/
     */
    public function addTenant()
    {
        assertNotNull($this->tenant->add());
    }
    
    /**
     * @Given /^I load tenant "([^"]*)"$/
     */
    public function loadTenant($domain)
    {
        $this->tenant->loadTenant("$domain$this->randTenantId.epals.com");
        assertNotNull($this->tenant);
    }
    
    /**
     * @Given /^domain in loaded tenant is "([^"]*)"$/
     */
    public function assertExistentTenant($domain)
    {
        assertEquals("$domain$this->randTenantId.epals.com",$this->tenant->getDomain());
    }
    
    /**
     * @Given /^I set tenant name "([^"]*)"$/
     */
    public function setTenantName($name)
    {
        $this->tenant->setName($name);
        assertEquals($name,$this->tenant->getName());
    }
    
    /**
     * @Given /^I set tenant domain "([^"]*)"$/
     */
    public function setTenantDomain($domain)
    {
        $this->tenant->setDomain($domain);
        assertEquals($domain,$this->tenant->getDomain());
    }
    
    /**
     * @Given /^I set tenant description "([^"]*)"$/
     */
    public function setTenantDescription($description)
    {
        $this->tenant->setDomain($description);
        assertEquals($domain,$this->tenant->getDescription());
    }
    
    /**
     * @Given /^I update tenant$/
     */
    public function updateTenant()
    {
        $this->tenant->update();
    }
    
    /**
     * @Given /^I check tenant name "([^"]*)"$/
     */
    public function checkTenantName($name)
    {
        assertEquals($this->tenant->getName(),$name);
    }
    
    /**
     * @Given /^I set tenant defaults$/
     */
    public function setTenantDefaults()
    {
        $this->tenant->setupDefaults();
    }
    
    //Steps for Group feature
    
    /**
     * @Given /^I create group "([^"]*)"$/
     */
    public function createAndAddNewGroupToTenant($groupName)
    {
        $this->group = new TeachersGroup();
        
        $this->group->setName($groupName+""+rand(0,1000));
        assertNotNull($this->group);
    }
    
    /**
     * @Given /^I add group for school "([^"]*)" and course "([^"]*)" in tenant "([^"]*)"$/
     */
    public function addGroup($school, $course, $domain)
    {
        assertNotNull($this->group->add($domain,$school,$course));
    }
    
    /**
     * @Given /^I delete group$/
     */
    public function deleteGroup()
    {
        $this->group->delete();
    }
    
    /**
     * @Then /^teacher is in group$/
     */
    public function checkTeacherIsInGroup()
    {
        assertTrue($this->group->isMember($this->teacher->getAccount()));
    }

    /**
     * @Then /^student is in group$/
     */
    public function checkStudentIsInGroup()
    {
        assertTrue($this->group->isMember($this->student->getAccount()));
    }

    /**
     * @Then /^teacher is not in group$/
     */
    public function checkTeacherIsNotInGroup()
    {
        assertFalse($this->group->isMember($this->teacher->getAccount()));
    }

    /**
     * @Then /^student is not in group$/
     */
    public function checkStudentIsNotInGroup()
    {
        assertFalse($this->group->isMember($this->student->getAccount()));
    }

    //Steps for Course
    
    /**
     * @Given /^I create course "([^"]*)" for school "([^"]*)" in tenant "([^"]*)"$/
     */
    public function createCourse($course, $school, $tenant)
    {
        $this->course =  new Course();
        $this->course->setCourseId($course);
        $this->course->setTitle($course);
        assertNotNull($this->course->add($tenant, $school));
    }
    
    //Steps for section
    
    /**
     * @Given /^I create section^/
     */
    public function createTestSection()
    {
        $this->section = new Section();
        assertNotNull($this->section);
    }
   
    //Steps for Teacher feature
    
    /**
     * @Given /^I create new teacher$/
     */
    public function createNewTeacher()
    {
        $this->teacher = new Teacher();
        assertNotNull($this->teacher);
    }
    
    /**
     * @Given /^I create teacher "([^"]*)" for tenant "([^"]*)"$/
     */
    public function createTeacherForTenant($username, $domain)
    {
        $this->teacher = new Teacher();
        $this->randUserId = rand(0,1000);
        $this->teacher->setAccount("$username$this->randUserId@$domain");
        $this->teacher->setEPalsEmail("$username$this->randUserId@mail.$domain");
        $this->teacher->setExternalEmail("$username$this->randUserId@mac.com");
        $this->teacher->setUserId("2_810_1_1_$this->randUserId");
        $this->teacher->setFirstName("$username$this->randUserId");
        $this->teacher->setLastName("$username$this->randUserId");
        $this->teacher->setPassword("Password123");
        $this->teacher->add();
    }
    
    /**
     * @Given /^I set teacher data$/
     */
    public function setTeacherData(TableNode $usersTable)
    {
        foreach ($usersTable->getHash() as $userHash){
            $this->teacher->setAccount($userHash['username'].$this->randUserId."@".$userHash['domain']);
            $this->teacher->setEPalsEmail("epals".$this->randUserId."@".$userHash['ePalsEmail']);
            $this->teacher->setExternalEmail("external".$this->randUserId."@".$userHash['externalEmail']);
            $this->teacher->setUserId($userHash['userId'].$this->randUserId);
            $this->teacher->setFirstName($userHash['firstName']);
            $this->teacher->setLastName($userHash['lastName']);
            $this->teacher->setPassword($userHash['password']);
        }
    }
    
    /**
     * @Given /^I add teacher to school "([^"]*)"$/
     */
    public function addTeacherToSchool($school)
    {
        assertNotNull($this->teacher->add($school));
    }
    
    /**
     * @Given /^I add teacher to group$/
     */
    public function addTeacherToGroup()
    {
        assertNotNull($this->group->addTeacherToGroup($this->teacher->getAccount()));
    }
    
    /**
     * @Given /^I remove teacher from group$/
     */
    public function removeTeacherFromGroup()
    {
        assertNotNull($this->group->removeTeacherFromGroup($this->teacher->getAccount()));
    }
    
    /**
     * @Given /^I add student to teacher$/
     */
    public function addStudentToTeacher()
    {
        assertNotNull($this->teacher->addStudent($this->student->getAccount()));
    }
    
    /**
     * @Given /^I add student to group$/
     */
    public function addStudentToGroup()
    {
   //     print_r($this->student->getAccount());
        assertNotNull($this->group->addStudentToGroup($this->student->getAccount()));
    }
    
    /**
     * @Given /^I remove student from teacher$/
     */
    public function removeStudentFromTeacher()
    {
        assertNotNull($this->teacher->removeStudent($this->student->getAccount()));
    }
    
    /**
     * @Given /^I remove student from group$/
     */
    public function removeStudentFromGroup()
    {
        assertNotNull($this->group->removeStudentFromGroup($this->student->getAccount()));
    }
    
    
    /**
     * @Given /^I update teacher$/
     */
    public function updateTeacher()
    {
        try{
            $this->message = $this->teacher->update();
            assertNotNull($this->message);
        } catch (Exception $ex) {
            $this->message = $ex->getMessage();
        }      
    }
    
    // Steps for Student
    
    /**
     * @Given /^I create new student/
     */
    public function createNewStudent()
    {
        $this->student = new Student();
        assertNotNull($this->student);
    }
    
    /**
     * @Given /^I create student "([^"]*)" for tenant "([^"]*)"$/
     */
    public function createStudentForTenant($username, $domain)
    {
        $this->student = new Student();
        $this->randUserId = rand(0,1000);
        $this->student->setAccount("$username$this->randUserId@$domain");
        $this->student->setEPalsEmail("$username$this->randUserId@mail.$domain");
        $this->student->setExternalEmail("$username$this->randUserId@mac.com");
        $this->student->setUserId("2_810_1_1_$this->randUserId");
        $this->student->setFirstName("$username$this->randUserId");
        $this->student->setLastName("$username$this->randUserId");
        $this->student->setPassword("Password123");
        $this->student->setRawDob("19800101");
        $this->student->setGrade("11");
        $this->student->add();      
        assertNotNull($this->student);
        assertNotNull($this->student->getAccount());
    }
    
    /**
     * @Given /^I set student data$/
     */
    public function setStudentData(TableNode $usersTable)
    {
        foreach ($usersTable->getHash() as $userHash){
            $this->student->setAccount($userHash['username'].$this->randUserId."@".$userHash['domain']);
            $this->student->setEPalsEmail("epals".$this->randUserId."@".$userHash['ePalsEmail']);
            $this->student->setExternalEmail("external".$this->randUserId."@".$userHash['externalEmail']);
            $this->student->setUserId($userHash['userId'].$this->randUserId);
            $this->student->setFirstName($userHash['firstName']);
            $this->student->setLastName($userHash['lastName']);
            $this->student->setPassword($userHash['password']);
            $this->student->setRawDob($userHash['rawDob']);
            $this->student->setGrade($userHash['grade']);
            assertNotNull($this->student->getAccount());       
        }
    }
    
    /**
     * @Given /^I add student to school "([^"]*)"$/
     */
    public function addStudentToSchool($school)
    {
        assertNotNull($this->student->add($school));
    }
    
    /**
     * @Given /^I update student$/
     */
    public function updateStudent()
    {
        try{
            $this->message = $this->student->update();
            assertNotNull($this->message);
        } catch (Exception $ex) {
            $this->message = $ex->getMessage();
        }
    }
    
    /**
     * @Given /^I add student to parent$/
     */
    public function addStudentToParent()
    {
        assertNotEmpty($this->parent->addStudent($this->student->getAccount()));
    }
    
    /**
     * @Given /^I remove student from parent$/
     */
    public function removeStudentFromParent()
    {
        assertNotEmpty($this->parent->removeParent($this->student->getAccount()));
    }
    
    /**
     * @Given /^I add moderator "([^"]*)" to student$/
     */
    public function addModeratorToStudent($moderator)
    {
        assertNotEmpty($this->student->addModerator("$moderator$this->randUserId@$this->domain"));
    }
    
    /**
     * @Given /^I remove moderator "([^"]*)" from teacher$/
     */
    public function removeModeratorFromTeacher($moderator)
    {
        assertNotEmpty($this->teacher->removeModerator("$moderator$this->randUserId"));
    }
    
    /**
     * @Then /^I remove moderator "([^"]*)" from student$/
     */
    public function removeModeratorFromStudent($arg1)
    {
        $this->student->removeModerator("$moderator$this->randUserId");
    }
    
    //Steps for Parent
    
    /**
     * @Given /^I create new parent/
     */
    public function createNewParent()
    {
        $this->parent = new Parent();
    }
    
    /**
     * @Given /^I create parent "([^"]*)" for tenant "([^"]*)"$/
     */
    public function createParentForTenant($username, $domain)
    {
        $this->parent = new Parental();
        $this->randUserId = rand(0,1000);
        $this->parent->setAccount("$username$this->randUserId@$domain");
        $this->parent->setEPalsEmail("$username$this->randUserId@mail.$domain");
        $this->parent->setExternalEmail("$username$this->randUserId@mac.com");
        $this->parent->setUserId("2_810_1_1_$this->randUserId");
        $this->parent->setFirstName("$username$this->randUserId");
        $this->parent->setLastName("$username$this->randUserId");
        $this->parent->setPassword("Password123");
        assertNotNull($this->parent->getAccount());
    }
    
    /**
     * @Given /^I set parent data$/
     */
    public function setParentData(TableNode $usersTable)
    {
        foreach ($usersTable->getHash() as $userHash){
            $this->parent->setAccount($userHash['username'].$this->randUserId."@".$userHash['domain']);
            $this->parent->setEPalsEmail("epals".$this->randUserId."@".$userHash['ePalsEmail']);
            $this->parent->setExternalEmail("external".$this->randUserId."@".$userHash['externalEmail']);
            $this->parent->setUserId($userHash['userId'].$this->randUserId);
            $this->parent->setFirstName($userHash['firstName']);
            $this->parent->setLastName($userHash['lastName']);
            $this->parent->setPassword($userHash['password']);
            assertNotNull($this->parent->getAccount());
        }
    }
    
    /**
     * @Given /^I add parent to school "([^"]*)"$/
     */
    public function addParentToSchool($school)
    {
        assertNotEmpty($this->parent->add($school));
    }
    
    /**
     * @Given /^I update parent$/
     */
    public function updateParent()
    {
        try{
            $this->message = $this->parent->update();
            assertNotNull($this->message);
        } catch (Exception $ex) {
            $this->message = $ex->getMessage();
            //assert expected exception message
        }
    }
    
    /**
     * @Given /^I add parent to student$/
     */
    public function addParentToStudent()
    {
        assertNotEmpty($this->student->addParent($this->parent->getAccount()));
    }
    
    /**
     * @Given /^I remove parent from student$/
     */
    public function removeParentFromStudent()
    {
        assertNotEmpty($this->student->removeParent($this->parent->getAccount()));
    }
    
    //Steps for School
    
    /**
     * @Given /^I create and add test school$/
     */
    public function createAndAddTestSchool()
    {
      $this->school = new School();
      $this->school->setDescription('Test School desc');
      $this->school->setSchoolId("SchoolQATest");
      $this->school->setName("School QA");
      $this->school->add('sfocil1.epals.com');
    }
    
    /**
     * @Given /^I create school "([^"]*)"$/
     */
    public function createSchool($name)
    {
        $this->randUserId = rand(0,1000);
        $this->school = new School();
        $this->school->setDescription('Test School $name$this->randUserId');
        $this->school->setSchoolId("SchoolQA$name$this->randUserId");
        $this->school->setName("$name$name$this->randUserId");
    }

    /**
     * @Given /^I add school in tenant "([^"]*)"$/
     */
    public function addSchool($tenant)
    {
        $this->school->add("$tenant");
    }

    /**
     * @When /^I check school "([^"]*)" in tenant "([^"]*)"$/
     */
    public function checkSchool($school, $tenant)
    {
        $this->school->exists($tenant, $school);
    }

    /**
     * @Then /^I load school "([^"]*)" from tenant "([^"]*)"$/
     */
    public function loadSchool($schoolId, $tenantDomain)
    {
        $this->school->loadSchool($tenantDomain, $schoolId);
    }

    /**
     * @When /^I set school name "([^"]*)"$/
     */
    public function setSchoolName($name)
    {
        $this->school->setName($name);
    }

    /**
     * @Given /^I add user to school$/
     */
    public function addUserToSchool()
    {
        $this->school->addUserToSchool($this->user->getAccount(), $this->user->getRoles());
    }

    /**
     * @Given /^I sleep for (\d+) seconds$/
     */
    public function sleep($seconds)
    {
      sleep($seconds);
    }
    
    //Steps for User Profile
    
    /**
     * @Given /^I approve profile using admin username "([^"]*)" and password "([^"]*)"$/
     */
    public function approveProfile($username, $password)
    {
      //echo 'Approving: ' . $this->profile_created_id;
      $admin_session = new Epals\Session();
      $res = $admin_session->login($username, $password);
      //echo "Created session for admin: " . $res->result->session_id . "\n";
      sleep(2); 
      $admin_profile = new Epals\Profile($admin_session);
      $response = $admin_profile->approve($this->profile_created_id);
      //print_r($response);
      //echo "profile approve: " . $response->status . "\n";
      //$created_id = $response->result->id;
      //echo 'Comparing '.$this->profile_created_id. ' == '. $response->result->id; 
      $this->profile_created_id = $response->result->id;
      assertNotEmpty($response->result->id);

    }
    
    /**
     * @Given /^I hold profile using admin username "([^"]*)" and password "([^"]*)" with comments "([^"]*)"$/
     */
    public function holdProfile($username, $password, $comments)
    {
      //echo 'Approving: ' . $this->profile_created_id;
      $admin_session = new Epals\Session();
      $res = $admin_session->login($username, $password);
      //echo "Created session for admin: " . $res->result->session_id . "\n";
      sleep(2); 
      $admin_profile = new Epals\Profile($admin_session);
      $response = $admin_profile->hold($this->profile_created_id, $comments);
      //print_r($response);
      //echo "profile approve: " . $response->status . "\n";
      //$created_id = $response->result->id;
      //echo 'Comparing '.$this->profile_created_id. ' == '. $response->result->id; 
      $this->profile_created_id = $response->result->id;
      assertNotEmpty($response->result->id);

    }
    
    
    
    /**
     * @Given /^Profile status is (approved|hold)$/
     */
    public function isProfileApproved($status)
    {
        $result = $this->profile->load($this->profile_created_id);
        assertNotNull($result);
        assertEquals($result->status, 'ok');
        assertTrue($result->result->user_id > 0);
        
        if($status == 'approved')
            assertTrue($result->result->approved);
        else if ($status == 'hold')
            assertFalse($result->result->approved);
        
        
    }
    
    /**
     * @Given /^I call get account and catch exception$/
     */
    public function getAccount()
    {
         $account= $this->profile->getAccount();
        
    }
    
    
    /**
     * @Given /^I create a user profile$/
     */
    public function createUserProfile()
    {
        $this->profile = new Epals\Profile();
        assertNotNull($this->profile);
    }
    
    /**
     * @Given /^I load last created user profile and verify (id|approve|schoolname|teachername|email|account|description|street1|street2|city|state|zip|country|phone|skypename|skypevisibility|agerange|numstudents|languages|subjects|collaboration|visibility) in profile$/
     */
    public function loadLastUserProfile($attribute)
    {
        $result = $this->profile->load($this->profile_created_id);
        assertNotNull($result);
        assertEquals($result->status, 'ok');
        assertTrue($result->result->user_id > 0);
        
        switch ($attribute)
        {
             case "id":
                assertNotEmpty($result->result->id);
                assertEquals($this->profile->getId(), $result->result->id);
            break;
            case "schoolname":
                assertNotEmpty($result->result->school_name);
                assertEquals($this->profile->getSchoolName(), $result->result->school_name);
            break;
            case "teachername":
                assertNotEmpty($result->result->teacher_name);
                assertEquals($this->profile->getTeacherName(), $result->result->teacher_name);
            break;
            case "email":
                assertNotEmpty($result->result->email);
                assertEquals($this->profile->getEmail(), $result->result->email);
            break;
            case "account":
                assertNotEmpty($result->result->account);
                assertEquals($this->profile->getAccount(), $result->result->account);
            break;
            case "description":
                assertNotEmpty($result->result->description);
                assertEquals($this->profile->getDescription(), $result->result->description);
            break;
            case "street1":
                assertNotEmpty($result->result->street1);
                assertEquals($this->profile->getStreet1(), $result->result->street1);
            break;
            case "street2":
                assertNotEmpty($result->result->street2);
                assertEquals($this->profile->getStreet2(), $result->result->street2);
            break;
            case "city":
                assertNotEmpty($result->result->city);
                assertEquals($this->profile->getCity(), $result->result->city);
            break;
            case "state":
                assertNotEmpty($result->result->state);
                assertEquals($this->profile->getState(), $result->result->state);
            break;
            case "country":
                 assertNotEmpty($result->result->country);
                assertEquals($this->profile->getCountry(), $result->result->country);
            break;
            case "phone":
                assertNotEmpty($result->result->phone);
                assertEquals($this->profile->getPhone(), $result->result->phone);
            break;
            case "skypename":
                assertNotEmpty($result->result->skype_name);
                assertEquals($this->profile->getSkypeName(), $result->result->skype_name);
            break;
            case "skypevisibility":
                assertNotEmpty($result->result->skype_visibility);
                assertEquals($this->profile->getSkypeVisibility(), $result->result->skype_visibility);
            break;
            case "agerange":
                assertNotEmpty($result->result->age_range);
                assertEquals($this->profile->getAgeRange(), $result->result->age_range);
            break;
            case "numstudents":
                assertNotEmpty($result->result->num_students);
                assertEquals($this->profile->getNumStudents(), $result->result->num_students);
            break;
            case "languages":
                assertNotEmpty($result->result->languages);
                assertEquals($this->profile->getLanguages(), $result->result->languages);
            break;
            case "subjects":
                assertNotEmpty($result->result->subjects);
                assertEquals($this->profile->getSubjects(), $result->result->subjects);
            break;
            case "collaboration":
                assertNotEmpty($result->result->collaboration);
                assertEquals($this->profile->getCollaboration(), $result->result->collaboration);
            break;
            case "visibility":
                assertNotEmpty($result->result->visibility);
                assertEquals($this->profile->getVisibility(), $result->result->visibility);
            break;
            case "approve":
                assertNotEmpty($result->result->approved);
                assertEquals($this->profile->isApproved(), $result->result->approved);
            break;
        
            default:
                throw new Exception("Unable to fine attr $attribute");
        
        }
        
        
    }
    
    /**
     * @Given /^I fail loading profile with message "([^"]*)"$/
     */
    public function failLoadingUserProfile()
    {
        $result = $this->profile->load($this->profile_created_id);
        //print_r($result);
        assertNotNull($result);
        assertEquals($result->status, 'fail');
       assertTrue(strpos($result->errors[0]->message,'ould not find a profile for the specified id')!== false);
    
        
        
    }
    
    
    /**
     * @Given /^I set (schoolname|teachername|email|account|description|street1|street2|city|state|zip|country|phone|skypename|skypevisibility|agerange|numstudents|languages|subjects|collaboration|visibility) "([^"]*)" in profile$/
     */
    public function setProfileProperty($attribute, $value)
    {
       
        switch ($attribute)
        {
            case "schoolname":
                $this->profile->setSchoolName($value);
            break;
            case "teachername":
                $this->profile->setTeacherName($value);
            break;
            case "email":
                $this->profile->setEmail($value);
            break;
            case "account":
                $this->profile->setAccount($value);
            break;
            case "description":
                $this->profile->setDescription($value);
            break;
            case "street1":
                $this->profile->setStreet1($value);
            break;
            case "street2":
                $this->profile->setStreet2($value);
            break;
            case "city":
                $this->profile->setCity($value);
            break;
            case "state":
                $this->profile->setState($value);
            break;
            case "country":
                $this->profile->setCountry($value);
            break;
            case "phone":
                $this->profile->setPhone($value);
            break;
            case "skypename":
                $this->profile->setSkypeName($value);
            break;
            case "skypevisibility":
                $this->profile->setSkypeVisibility($value);
            break;
            case "agerange":
                $this->profile->setAgeRange($value);
            break;
            case "numstudents":
                $this->profile->setNumStudents($value);
            break;
            case "languages":
                $this->profile->setLanguages($value);
            break;
            case "subjects":
                $this->profile->setSubjects($value);
            break;
            case "collaboration":
                $this->profile->setCollaboration($value);
            break;
            case "visibility":
                $this->profile->setVisibility($value);
            break;
        
        }
        
     
    }
    
    
    
    /**
     * @Given /^I set profile info$/
     */
    public function setProfileInfo()
    {
        //$p = new Profile();
       
        $this->profile->setAccount("harrypotter@epals.com");
        $this->profile->setDescription("My name is harry potter. I like to play Quidditch and make an effort at becoming a good magician. I'm looking for information on my parents.");
        $this->profile->setCountry("GB");
        $this->profile->setCity("London");
        //$this->profile->setName("Harry Potter");
        $this->profile->setZip("E17");
        assertNotEmpty($this->profile->getAccount());
    }
    
    /**
     * @Given /^I set zip "([^"]*)"$/
     */
    public function setZip($zip)
    {
        $this->profile->setZip($zip);
        assertNotEmpty($this->profile->getZip());
    }
    
    
    
    /** 
     * @Given /^I add profile and get Exception$/
     * @expectedException Exception
     */
    public function addProfileWithException()
    {
        
        $res = $this->profile->add();  
        //print_r($res);
     //   assertNotEmpty($res->result->id);
     //   assertEquals($res->status,'ok');
       // $this->profile_created_id = $res->result->id;
       // echo 'I added : ' . $this->profile_created_id;
    }
    
    
    /** 
     * @Given /^I add profile$/
     */
    public function addProfile()
    {
        
        $res = $this->profile->add();  
     //  print_r($res);
        assertNotEmpty($res->result->id);
        assertEquals($res->status,'ok');
        $this->profile_created_id = $res->result->id;
       // echo 'I added : ' . $this->profile_created_id;
    }
    
    /** 
     * @Given /^I update profile$/
     */
    public function updateProfile()
    {
        
        $res = $this->profile->update($this->profile_created_id);  
        //print_r($res);
        assertNotEmpty($res->result->id);
        assertEquals($res->status,'ok');
        $this->profile_created_id = $res->result->id;
         echo 'I updated : ' . $this->profile_created_id;
    }
    
    
    /** 
     * @Given /^I fail adding profile with message "([^"]*)"$/
     */
    public function failAddProfile($message)
    {
        $res = $this->profile->add(); 
        assertEquals($res->status,'fail');
        assertEquals($message, $res->errors[0]->message);
        $this->profile_created_id = 0;
    }
    
    
    
    /**
     * @Given /^I delete profile$/
     */
    public function deleteProfile()
    {
        $res = $this->profile->delete($this->profile_created_id);
       // print_r($res);
        assertEquals($res->status, 'ok');
       
      
    }
    
    //Steps for User Attribute
    
    /**
     * @Given /^I create user attribute for user$/
     */
    public function createUserAttribute()
    {
        //print_r($this->user);
        $this->ua = new UserAttribute($this->user->getAccount());
        //assertNotNull($this->ua);
        //$this->ua;
    }
    
    /**
     * @Given /^I get attribute "([^"]*)"$/
     */
    public function getAttribute($attributeName)
    {
        //assertNotEmpty($this->ua->get($attributeName));
        $this->ua->get($attributeName);
    }
    
    /**
     * @Given /^I get all attributes$/
     */
    public function getAllAttributes()
    {
        //assertNotEmpty($this->ua->getAll());
        $this->ua->getAll();
    }
    
    /**
     * @Given /^I update user attribute$/
     */
    public function updateAttribute()
    {
        $res = $this->ua->update();
        //assertNotNull($res);
    }
    
    /**
     * @Given /^I delete user attributes$/
     */
    public function deleteAttribute()
    {
        $res = $this->ua->delete();
        //assertNotEmpty($res);
    }
    
    /**
     * @Given /^I add attribute "([^"]*)" with value "([^"]*)"$/
     */
    public function addAttribute($attributeName, $value)
    {
        $res = $this->ua->add($attributeName, $value);
        //assertNotEmpty($res);
    }
    
    /**
     * @Given /^I create user attribute for "([^"]*)"$/
     */
    public function createUserAttributeForSpecificUser($username)
    {
        //echo ("$user$this->randUserId&$this->tenant_name");
        $this->up = new UserAttribute($username);
        //assertNotNull($this->up);
    }
    
    //Steps for User Preference
    
    /**
     * @Given /^I create user preference for user$/
     */
    public function createUserPreference()
    {
        //echo ("$user$this->randUserId&$this->tenant_name");
        $this->up = new UserPreference($this->user->getAccount());
        //assertNotNull($this->up);
    }
    
    /**
     * @Given /^I create user preference for "([^"]*)"$/
     */
    public function createUserPreferenceForSpecificUser($username)
    {
        //echo ("$user$this->randUserId&$this->tenant_name");
        $this->up = new UserPreference($username);
        //assertNotNull($this->up);
    }
    
    /**
     * @Given /^I get preference "([^"]*)"$/
     */
    public function getPreference($attributeName)
    {
        //assertNotEmpty($this->up->get($attributeName));
        $this->up->get($attributeName);
    }
    
    /**
     * @Given /^I get all preferences$/
     */
    public function getAllPreferences()
    {
        //assertNotEmpty($this->up->getAll());
        $this->up->getAll();
    }
    
    /**
     * @Given /^I update user preferences$/
     */
    public function updatePreferences()
    {
        $res = $this->up->update();
        //assertNotEmpty($res);
    }
    
    /**
     * @Given /^I delete preferences$/
     */
    public function deletePreferences()
    {
        $res = $this->up->delete();
        //assertNotEmpty($res);
    }
    
    /**
     * @Given /^I add user preference "([^"]*)" with value "([^"]*)"$/
     */
    public function addPreference($preferenceName, $value)
    {
        $res = $this->up->add($preferenceName, $value);
        //assertNotEmpty($res);
    }
    
    //Steps for Content
    
    /**
     * @Given /^I create content$/
     */
    public function createContent()
    {
        $this->content = new Content();
        assertNotNull($this->content);
    }
    
    /**
     * @Given /^I set tenant to "([^"]*)"$/
     */
    public function setTenant($tenant)
    {
        $this->content->setTenant($tenant);
        assertEquals($tenant,$this->content->getTenant());
    }
    
    
    /**
     * @Given /^I set tenant to "([^"]*)" and get exception with message "([^"]*)"$/
     */
    public function setTenantWithException($tenant, $message)
    {
        try
        {
            $this->content->setTenant($tenant);
            //assertEquals($tenant,$this->content->getTenant());
        }catch(Exception $e)
        {
            assertEquals($message,$e->getMessage());
            return;
        }
        assertTrue(false);
    }
    
    
    /**
     * @Given /^I set author to "([^"]*)"$/
     */
    public function setAuthor($author)
    {
        $this->content->author = $author;
        assertEquals($author,$this->content->author);
    }
    
    /**
     * @Given /^I set data to "([^"]*)"$/
     */
    public function setData($data)
    {
        $this->content->data = $data;
        assertEquals($data,$this->content->data);
    }
    
    /**
     * @Given /^I set url to "([^"]*)"$/
     */
    public function setUrl($url)
    {
        $this->content->url = $url;
        assertEquals($url,$this->content->url);
    }
    
    /**
     * @Given /^I set title to "([^"]*)"$/
     */
    public function setTitle($title)
    {
        $this->content->title = $title;
        assertEquals($title,$this->content->title);
    }
    
    /**
     * @Given /^I add content$/
     */
    public function addContent()
    {
        $result = $this->content->add();
       // print_r($result);
        assertNotEmpty($result["_id"]);
        assertEquals($result["_type"],'content');
        $this->last_created_content_id = $result["_id"];
    }
    
    
    /**
     * @Given /^I load last created content$/
     */
    public function loadLastContent()
    {
        $this->content = new Content($this->last_created_content_id);
       // print_r($this->content);
        //assertNotEmpty($this->content->getName());
       // assertEquals($result["_type"],'content');
       // $this->last_created_content_id = $result["_id"];
    }
    
    
    /**
     * @Given /^I get exception on add content with message "([^"]*)"$/
     */
    public function addContentWithException($message)
    {
        try
        {
            $result = $this->content->add();
        }
        catch(Exception $e)
        {
              assertEquals($message,$e->getMessage());
              return;
        }
        assertTrue(false);
    }
    
    
    
    
     /**
     * @Given /^I get content by key "([^"]*)" "([^"]*)"$/
     */
    public function getContentByKey($key, $value)
    {
        $result = $this->content->getByKey($key, $value);
        assertTrue(sizeof($result) > 0);
       
        foreach ($result as $value) {
            assertEquals($value["_type"],"content");
            assertEquals($value["_source"]["author"], $value);
        }
        
    }
    
    
    /**
     * @Given /^I lookup content by key "([^"]*)" "([^"]*)" with data "([^"]*)"$/
     */
    public function getContentByKeyAndData($key, $value, $data)
    {
        $result = $this->content->getByKey($key, $value);
        assertTrue(sizeof($result) > 0);
       // print_r($result);
        
        foreach ($result as $val) {
            if($val["_type"] == "content" && $val["_source"]["author"] == $value & $val["_source"]["data"] == $data)
            {
                assertTrue(true);
                return;
            }
        }
       
        assertTrue(false);
    }
    
    /**
     * @Given /^I update content$/
     */
    public function updateContent()
    {
        $result= $this->content->update();
        assertNotEmpty($result["_id"]);
        assertEquals($result["_type"],'content');
    }
    
    /**
     * @Given /^I delete content$/
     */
    public function deleteContent()
    {
        $result= $this->content->delete();
        assertNotEmpty($result["_id"]);
        assertEquals($result["_type"],'content');
    }
    
    //Steps for Event
    
    /**
     * @Given /^I create event$/
     */
    public function createEvent()
    {
        $this->event = new Event();
        assertNotNull($this->event);
    }
    
    
    /**
     * @Given /^I create session using username "([^"]*)" and password "([^"]*)"/
     */
    public function createSession($username, $password)
    {
        $this->session = new Epals\Session();
        $this->login = $this->session->login($username, $password);
        //$session_id = $login_object->result->session_id;
        //print_r($this->login);
        sleep(1);
    }
    
    
    /**
     * @Given /^I create session by broker using username "([^"]*)" and password "([^"]*)"/
     */
    public function createSessionByBroker($username, $password)
    {
        $this->session_broker = new Epals\SessionBroker();
        $this->session = $this->session_broker->login($username, $password);
        
        sleep(1);
    }
    
    
    
    /**
     * @Given /^I set session to profile$/
     */
    public function setSessionToProfile($username, $password)
    {
        // setAccount does not ask for credentials, it would be for admin edits, setSessio       
        $this->profile->setSession($this->login->result->session_id); 
    }
     
    /**
     * @Given /^I set event type "([^"]*)"$/
     */
    public function setEventType($type)
    {
        $this->event->setType($type);
        //assertEquals($type,$this->event->getType());
    }
    
    /**
     * @Given /^I include data "([^"]*)" "([^"]*)"$/
     */
    public function includeDataToArray($dataName, $dataValue)
    {
        $data[$dataName] = $dataValue;
    }
    
    /**
     * @Given /^I set event data "([^"]*)"$/
     */
    public function setEventData($data)
    {
        $this->event->setData($data);
        //assertEquals($data, $this->event->getData());
    }
    
    /**
     * @Given /^I set callback "([^"]*)"$/
     */
    public function setCallback($callbackName)
    {
        $this->event->setCallBack($callbackName);
        //assertEquals($callbackName, $this->event->getCallback());
    }
    
    /**
     * @Given /^I add event$/
     */
    public function addEvent()
    {
        $result = $this->event->add();
        assertNotEmpty($result);
        assertNotEmpty($result['_id']);   
    }
    
    /**
     * @Given /^I delete event$/
     */
    public function deleteEvent()
    {
        $result= $this->event->delete();
        assertNotEmpty($result["_id"]);
        assertEquals($result["_type"],'event');
    }
    
    /**
     * @Given /^I get event$/
     */
    public function getEvent()
    {
        $result= $this->event->get();
    }
    
    /**
     * @Given /^I get event by key "([^"]*)" "([^"]*)"$/
     */
    public function getEventByKey($key, $value)
    {
        $result= $this->event->getByKey($key, $value);
    }
    
    /**
     * @Then /^I delete user$/
     */
    public function deleteUser()
    {
        $this->user->delete();
    }
    
    /**
     * @Given /^I create policy "([^"]*)"$/
     */
    public function createPolicy($name)
    {
        $this->policy = new Policy($name);
        $this->policy->save();
    }
    
    /**
     * @When /^I save policy$/
     */
    public function savePolicy()
    {
        $this->policy->save();
    }
    
    /**
     * @Then /^I deny word "([^"]*)"$/
     */
    public function denyWord($word)
    {
        $this->policy->deny($this->role, $word);
    }
    
    /**
     * @Then /^I allow word "([^"]*)"$/
     */
    public function allowWord($word)
    {
        $this->policy->allow($this->role, $word);
    }
    
    /**
     * @Given /^I create role "([^"]*)"$/
     */
    public function createRole($name)
    {
        $this->role = new Role($name);
    }
    
    /**
     * @Then /^word "([^"]*)" is allowed in role "([^"]*)"$/
     */
    public function isAllowed($word, $roleName)
    {
        assertTrue($this->policy->isAllowed($roleName, $word));
    }
    
    /**
     * @Then /^word "([^"]*)" is denied in role "([^"]*)"$/
     */
    public function isNotAllowed($word, $roleName)
    {
        assertFalse($this->policy->isAllowed($roleName, $word));
    }
    
    // Steps for Community
    
    /**
     * @Given /^I create community$/
     */
    public function createCommunity()
    {
        $this->community = new Community();
    }
    
    /**
     * @Given /^I create and add community "([^"]*)"$/
     */
    public function createAndAddCommunity($name)
    {
        $this->community = new Community();
        if(!isset($name) || trim($name)==='') {
            $this->community->setName("$name");
        }
        else {
            $this->community->setName("$name". rand(0,1000));
        }
        $this->community->setDescription("Community description");
        $this->community->setSsorealm("http://test.epals.com/sso/");
        $this->community->add();
    }
    
    /**
     * @Given /^I set community name "([^"]*)"$/
     */
    public function setCommunityName($name)
    {
        $this->community->setName($name);
    }
    
    /**
     * @Given /^I set community description "([^"]*)"$/
     */
    public function setCommunityDescription($description)
    {
        $this->community->setDescription($description);
    }
    
    /**
     * @Given /^I set community SSO realm "([^"]*)"$/
     */
    public function setCommunitySsoRealm($ssorealm)
    {
        $this->community->setSsorealm($ssorealm);
    }
    
    /**
     * @Given /^I load last community$/
     */
    public function loadCommunity()
    {
        $this->community->loadCommunityById($this->community->getId());
    }
    
    /**
     * @Given /^I load community by id "([^"]*)"$/
     */
    public function loadCommunityById($id)
    {
        $this->community->loadCommunityById($id);
    }
    
    /**
     * @Given /^I load community by name "([^"]*)"$/
     */
    public function loadCommunityByName($name)
    {
        $this->community->loadCommunityByName($name);
    }    
    
    /**
     * @Given /^I check community name is "([^"]*)"$/
     */
    public function checkCommunityName($name)
    {
        assertEquals($name,$this->community->getName());
    }
    
    /**
     * @Given /^I check community description is "([^"]*)"$/
     */
    public function checkCommunityDescription($description)
    {
        assertEquals($description,$this->community->getDescription());
    }
    
    /**
     * @Given /^I check community SSO is "([^"]*)"$/
     */
    public function checkCommunitySSO($sso)
    {
        assertEquals($sso,$this->community->getSsorealm());
    }
    
    /**
     * @Given /^I add community$/
     */
    public function addCommunity()
    {
        $this->community->add();
    }
    
    /**
     * @Given /^I update community$/
     */
    public function updateCommunity()
    {
        $this->community->update();
    }
    
    /**
     * @Given /^I add tenant "([^"]*)" to community$/
     */
     public function addTenantToCommunity($tenantDomain)
     {
         $this->community->addTenant($tenantDomain);
     }
     
     // Steps for LookUps
     
     /**
     * @Given /^I create country lookup$/
     */
     public function createCountryLookup()
     {
        $this->country_lookup = new Epals\CountryLookup($this->session);
     }
     
     /**
     * @Given /^I lookup all countries$/
     */
     public function lookupCountry()
     {
        $all_countries = $this->country_lookup->getAllCountries();
        assertNotNull($all_countries);
        assertTrue(sizeof($all_countries) > 0);
        //print_r($all_countries);
     }
     
     /**
     * @Given /^I lookup country by code "([^"]*)"$/
     */
     public function lookupCountryByCode($country_code)
     {
        $country = $this->country_lookup->getCountryName($country_code);
        assertEquals($country, "USA");
     }
     
     /**
     * @Given /^I lookup country by invalid code "([^"]*)"$/
     */
     public function lookupCountryByInvalidCode($country_code)
     {
        $country = $this->country_lookup->getCountryName($country_code);
        assertNull($country);
     }
     
     
     /**
     * @Given /^I lookup country by name "([^"]*)"$/
     */
     public function lookupCountryByName($country_name)
     {
        $country = $this->country_lookup->getCountryCode($country_name);
        assertEquals($country, "us");
     }
     
     /**
     * @Given /^I lookup country by invalid name "([^"]*)"$/
     */
     public function lookupCountryByInvalidName($country_name)
     {
        $country = $this->country_lookup->getCountryCode($country_name);
        assertNull($country);
     }
     
     /**
     * @Given /^I lookup provinces by country code "([^"]*)"$/
     */
     public function lookupProvincesByCode($country_code)
     {
        $provinces = $this->country_lookup->getCountryProvinces($country_code);
        assertNotNull($provinces);
        assertTrue(sizeof($provinces) > 0);
     }
     
     /**
     * @Given /^I lookup provinces by invalid country code "([^"]*)"$/
     */
     public function lookupProvincesByInvalidCode($country_code)
     {
        $provinces = $this->country_lookup->getCountryProvinces($country_code);
        assertNull($provinces);
        
     }
     
     
     // SchoolType Lookup
     
     /**
     * @Given /^I create schooltype lookup$/
     */
     public function createSchoolTypeLookup()
     {
        $this->schooltype_lookup = new Epals\SchoolTypeLookup($this->session);
     }
     
     
     /**
     * @Given /^I lookup all schooltypes$/
     */
     public function lookupAllSchoolType()
     {
        $types = $this->schooltype_lookup->getAllSchoolTypes();
        assertNotNull($types);
        assertTrue(sizeof($types) > 0);
     }
     
     /**
     * @Given /^I lookup schooltype id "([^"]*)" by name "([^"]*)"$/
     */
     public function lookupSchoolTypeName($school_type_id, $school_type_name)
     {
        $types = $this->schooltype_lookup->getSchoolTypeId($school_type_name);
        assertEquals($school_type_id, $types);
     //   print_r($types); 
     }
     
     /**
     * @Given /^I lookup schooltype by invalid name "([^"]*)"$/
     */
     public function lookupSchoolTypeInvalidName($school_type_name)
     {
        $types = $this->schooltype_lookup->getSchoolTypeId($school_type_name);
        assertNull($types);
      //  print_r($types); 
     }
     
     
     /**
     * @Given /^I lookup schooltype name "([^"]*)" by id "([^"]*)"$/
     */
     public function lookupSchoolTypeId($school_type_name, $school_type_id)
     {
        $types = $this->schooltype_lookup->getSchoolTypeName($school_type_id);
        assertEquals($types, $school_type_name);
      //  print_r($types); 
     }
     
     /**
     * @Given /^I lookup schooltype by invalid id "([^"]*)"$/
     */
     public function lookupSchoolTypeInvalidId($school_type_id)
     {
        $types = $this->schooltype_lookup->getSchoolTypeName($school_type_id);
        assertNull($types);
       // print_r($types); 
     }
     
     // Age range lookup
     
     /**
     * @Given /^I create agerange lookup$/
     */
     public function createAgeRangeLookup()
     {
        $this->agerange_lookup = new Epals\AgeRangeLookup($this->session);
     }
 
     /**
     * @Given /^I lookup all ageranges$/
     */
     public function lookupAllAgeRanges()
     {
        $ranges = $this->agerange_lookup->getAllAgeRanges();
        assertNotNull($ranges);
        assertTrue(sizeof($ranges) > 0);
      //  print_r($ranges);
     }
     
     /**
     * @Given /^I lookup agerange "([^"]*)" by id "([^"]*)"$/
     */
     public function lookupAgeRangeId($age_range, $age_range_id)
     {
        $range = $this->agerange_lookup->getAgeRangeName($age_range_id);
        assertEquals($range, $age_range);
        //print_r($range); 
     }
     
     /**
     * @Given /^I lookup agerange by invalid id "([^"]*)"$/
     */
     public function lookupAgeRangeInvalidId($age_range_id)
     {
        $range = $this->agerange_lookup->getAgeRangeName($age_range_id);
        assertNull($range);
        //print_r($range); 
     }
    
     /**
     * @Given /^I lookup agerange id "([^"]*)" by name "([^"]*)"$/
     */
     public function lookupAgeRangeName($age_range_id, $age_range)
     {
        $range = $this->agerange_lookup->getAgeRangeId($age_range);
        assertEquals($age_range_id, $range);
     //   print_r($range); 
     }
     
     /**
     * @Given /^I lookup agerange by invalid name "([^"]*)"$/
     */
     public function lookupAgeRangeInvalidName($age_range)
     {
        $range = $this->agerange_lookup->getAgeRangeId($age_range);
        assertNull($range);
       // print_r($range); 
     }
     
     // GradeLookup
     
     
      /**
     * @Given /^I create grade lookup$/
     */
     public function createGradeLookup()
     {
        $this->grade_lookup = new Epals\GradeLookup($this->session);
     }
     
     /**
     * @Given /^I lookup all grades$/
     */
     public function lookupAllGrades()
     {
        $grades = $this->grade_lookup->getAllGrades();
        assertNotNull($grades);
        assertTrue(sizeof($grades) > 0);
        //print_r($grades);
     }
     
     /**
     * @Given /^I lookup grade "([^"]*)" by id "([^"]*)"$/
     */
     public function lookupGradeId($grade, $grade_id)
     {
        $gradename = $this->grade_lookup->getGradeName($grade_id);
        assertEquals($gradename, $grade);
       // print_r($gradename); 
     }
     
     /**
     * @Given /^I lookup grade by invalid id "([^"]*)"$/
     */
     public function lookupGradeInvalidId($grade_id)
     {
        $rangename = $this->grade_lookup->getGradeName($grade_id);
        assertNull($rangename);
        //print_r($range); 
     }
    
     /**
     * @Given /^I lookup grade id "([^"]*)" by name "([^"]*)"$/
     */
     public function lookupGradeName($grade_id, $grade)
     {
        $gradeid = $this->grade_lookup->getGradeId($grade);
        assertEquals($gradeid, $grade_id);
        //print_r($range); 
     }
     
     /**
     * @Given /^I lookup grade by invalid name "([^"]*)"$/
     */
     public function lookupGradeInvalidName($grade)
     {
        $grade_id = $this->grade_lookup->getGradeId($grade);
        assertNull($grade_id);
        //print_r($grade_id); 
     }

     
     // RoleLookup
     
     
     /**
     * @Given /^I create role lookup$/
     */
     public function createRoleLookup()
     {
        $this->role_lookup = new Epals\RoleLookup($this->session);
     }
     
     /**
     * @Given /^I lookup all roles$/
     */
     public function lookupAllRoles()
     {
        $roles = $this->role_lookup->getAllRoles();
        assertNotNull($roles);
        assertTrue(sizeof($roles) > 0);
        print_r($roles);
     }
     
     /**
     * @Given /^I lookup role "([^"]*)" by id "([^"]*)"$/
     */
     public function lookupRoleId($role, $role_id)
     {
        $rolename = $this->role_lookup->getRoleName($role_id);
        assertEquals($rolename, $role);
       // print_r($gradename); 
     }
     
     /**
     * @Given /^I lookup role by invalid id "([^"]*)"$/
     */
     public function lookupRoleInvalidId($role_id)
     {
        $rolename = $this->role_lookup->getRoleName($role_id);
        assertNull($rolename);
        //print_r($range); 
     }
    
     /**
     * @Given /^I lookup role id "([^"]*)" by name "([^"]*)"$/
     */
     public function lookupRoleName($role_id, $role)
     {
        $roleid = $this->role_lookup->getRoleId($role);
        assertEquals($roleid, $role_id);
        //print_r($range); 
     }
     
     /**
     * @Given /^I lookup role by invalid name "([^"]*)"$/
     */
     public function lookupRoleInvalidName($role)
     {
        $role_id = $this->role_lookup->getRoleId($role);
        assertNull($role_id);
        //print_r($grade_id); 
     }
     
  }


?>
