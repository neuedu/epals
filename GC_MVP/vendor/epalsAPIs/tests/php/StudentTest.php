<?php


require_once("../../php/teacher.php");
require_once("../../php/student.php");
require_once("../../php/parental.php");
require_once('testutility.php');

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-06 at 17:51:38.
 */
class StudentTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Student
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Student;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Student::setRoles
     * @todo   Implement testSetRoles().
     * @expectedException Exception
     */
    public function testSetRoles() {
       
         
        $t = new Student();
        $role = array('Student');
        $t->setRoles($role);
        
    }


    /**
     * @covers Student::add
     * @todo   Implement testAdd().
     */
    public function testAdd() {
        
        $tenanttoken = strtolower(TestUtility::generateRandomString(5));

        $t = new Tenant();

        $t->setDomain("$tenanttoken.test.com");
        $t->setEmailDomain("$tenanttoken.mail.test.com");
        $t->setPublished('false');
        $t->setName($tenanttoken."town");
        $result = $t->add();
        $this->assertEquals(1, $result->NodeId > 0);


        // Add Student in Tenant
        $s1 = new Student();
        $s1->setAccount("student1_$tenanttoken@$tenanttoken.test.com");
        $s1->setEPalsEmail("student1_$tenanttoken@$tenanttoken.mail.test.com");
        $s1->setExternalEmail("student1_$tenanttoken@$tenanttoken.com");
        $s1->setUserId("S1");
        $s1->setFirstName("FName1");
        $s1->setLastName("LName1");
        $s1->setPassword("Password123");
        $s1->setRawDob("19960101");
        $s1->setGrade("11");
       
        $result1 = $s1->add();
        $this->assertEquals(1, $result1->NodeId > 0);
        return $tenanttoken;
        
        
    }
    
    /**
     * @covers Student::addModerator
     * @todo   Implement testAddModerator().
     * @depends testAdd
     */
    public function testAddModerator($tenanttoken) {
        
        
         // Add Student in Tenant
        $t1 = new Teacher();
        $t1->setAccount("teacher1_$tenanttoken@$tenanttoken.test.com");
        $t1->setEPalsEmail("teacher1_$tenanttoken@$tenanttoken.mail.test.com");
        $t1->setExternalEmail("teacher1_$tenanttoken@$tenanttoken.com");
        $t1->setUserId("T1");
        $t1->setFirstName("FName1");
        $t1->setLastName("LName1");
        $t1->setPassword("Password123");
        
        $result1 = $t1->add();
        
        $this->assertEquals(1, $result1->NodeId > 0);
        
        $s = new Student("student1_$tenanttoken@$tenanttoken.test.com");
        
        $result2 = $s->addModerator("teacher1_$tenanttoken@$tenanttoken.test.com");
        
        $this->assertEquals("student1_$tenanttoken@$tenanttoken.test.com", $result2->StudentAccountId);
        $this->assertEquals("teacher1_$tenanttoken@$tenanttoken.test.com", $result2->ModeratorAccountId);
     
        return $tenanttoken;
    }

    /**
     * @covers Student::removeModerator
     * @todo   Implement testRemoveModerator().
     * @depends testAddModerator
     */
    public function testRemoveModerator($tenanttoken) {
        
        
        $s = new Student("student1_$tenanttoken@$tenanttoken.test.com");
        
        $result = $s->removeModerator("teacher1_$tenanttoken@$tenanttoken.test.com");
        
         $this->assertEquals("Success", $result);
        
    }

    
     /**
     * @covers Student::addMentor
     * @todo   Implement testAddMentor().
     * @depends testAdd
     */
    public function testAddMentor($tenanttoken) {
        
        
         // Add Student in Tenant
        $t1 = new Teacher();
        $t1->setAccount("teacher2_$tenanttoken@$tenanttoken.test.com");
        $t1->setEPalsEmail("teacher2_$tenanttoken@$tenanttoken.mail.test.com");
        $t1->setExternalEmail("teacher2_$tenanttoken@$tenanttoken.com");
        $t1->setUserId("T2");
        $t1->setFirstName("FName2");
        $t1->setLastName("LName2");
        $t1->setPassword("Password123");
        
        $result1 = $t1->add();
        
        $this->assertEquals(1, $result1->NodeId > 0);
        
        $s = new Student("student1_$tenanttoken@$tenanttoken.test.com");
        
        $result2 = $s->addMentor("teacher2_$tenanttoken@$tenanttoken.test.com");
        
        $this->assertEquals("student1_$tenanttoken@$tenanttoken.test.com", $result2->StudentAccountId);
        $this->assertEquals("T2", $result2->MentorExternalId);
     
        return $tenanttoken;
    }

    /**
     * @covers Student::removeMentor
     * @todo   Implement testRemoveMentor().
     * @depends testAddMentor
     */
    public function testRemoveMentor($tenanttoken) {
        
        
        $s = new Student("student1_$tenanttoken@$tenanttoken.test.com");
        
        $result = $s->removeMentor("teacher2_$tenanttoken@$tenanttoken.test.com");
        
         $this->assertEquals("Success", $result);
        
    }
    
    
    
    /**
     * @covers Student::addParent
     * @todo   Implement testAddParent().
     * @depends testAdd
     */
    public function testAddParent($tenanttoken) {
        
        // Add Student in Tenant
        $t1 = new Parental();
        $t1->setAccount("parent1_$tenanttoken@$tenanttoken.test.com");
        $t1->setEPalsEmail("parent1_$tenanttoken@$tenanttoken.mail.test.com");
        $t1->setExternalEmail("parent1_$tenanttoken@$tenanttoken.com");
        $t1->setUserId("P1");
        $t1->setFirstName("FName1");
        $t1->setLastName("LName1");
        $t1->setPassword("Password123");
        
        $result1 = $t1->add();
        
        $this->assertEquals(1, $result1->NodeId > 0);

        $s = new Student("student1_$tenanttoken@$tenanttoken.test.com");
        
        $result = $s->addParent("parent1_$tenanttoken@$tenanttoken.test.com");
        
        $this->assertEquals("student1_$tenanttoken@$tenanttoken.test.com", $result->StudentAccountId);
        $this->assertEquals("parent1_$tenanttoken@$tenanttoken.test.com", $result->ParentAccountId);
   
        return $tenanttoken;     
    }

    /**
     * @covers Student::removeParent
     * @todo   Implement testRemoveParent().
     * @depends testAddParent
     */
    public function testRemoveParent($tenanttoken) {
       
        $s = new Student("student1_$tenanttoken@$tenanttoken.test.com");
        
        $result = $s->removeParent("parent1_$tenanttoken@$tenanttoken.test.com");
        
        $this->assertEquals("Success", $result);
    
    }
}