<?php

require_once("../../php/parental.php");
require_once("../../php/student.php");
require_once('testutility.php');

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-06 at 18:38:09.
 */
class ParentalTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Parental
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Parental;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Parental::setRoles
     * @todo   Implement testSetRoles().
     * @expectedException Exception
     */
    public function testSetRoles() {
       
        
        $t = new Parental();
        $role = array('Parennt');
        $t->setRoles($role);
        
    }

    /**
     * @covers Parental::setRawDob
     * @todo   Implement testSetRawDob().
     * @expectedException Exception
     */
    public function testSetRawDob() {
        
       $p = new Parental();
       $p->setRawDob('1998-01-01');
        
        
    }

    /**
     * @covers Parental::setGrade
     * @todo   Implement testSetGrade().
     * @expectedException Exception
     */
    public function testSetGrade() {
        
        $p = new Parental();
        $p->setGrade('11');
        
    }

    /**
     * @covers Parental::getUserType
     * @todo   Implement testGetUserType().
     */
    public function testGetUserType() {
        
         $p = new Parental();
         $this->assertEquals("Parent", $p->getUserType());
        
    }
    
    
    
    /**
     * @covers Parental::add
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

        
        $p1 = new Parental();
        $p1->setAccount("parent1_$tenanttoken@$tenanttoken.test.com");
        $p1->setEPalsEmail("parent1_$tenanttoken@$tenanttoken.mail.test.com");
        $p1->setExternalEmail("parent1_$tenanttoken@$tenanttoken.com");
        $p1->setUserId("P58_2_1");
        $p1->setFirstName("Edward");
        $p1->setLastName("Bell");
        $p1->setPassword("Password123");
        
        $result1 = $p1->add();
        
        $this->assertEquals(1, $result1->NodeId > 0);
        
        return $tenanttoken;
    }
    

    /**
     * @covers Parental::addStudent
     * @todo   Implement testAddStudent().
     * @depends testAdd
     */
    public function testAddStudent($tenanttoken) {
        
        $p1 = new Parental("parent1_$tenanttoken@$tenanttoken.test.com");
        
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
        
        $result2 = $p1->addStudent("student1_$tenanttoken@$tenanttoken.test.com");
        
        $this->assertEquals("student1_$tenanttoken@$tenanttoken.test.com", $result2->StudentAccountId);
        $this->assertEquals("parent1_$tenanttoken@$tenanttoken.test.com", $result2->ParentAccountId);
   
        return $tenanttoken;
    }

    /**
     * @covers Parental::removeStudent
     * @todo   Implement testRemoveStudent().
     * @depends testAddStudent
     */
    public function testRemoveStudent($tenanttoken) {
       
         $p1 = new Parental("parent1_$tenanttoken@$tenanttoken.test.com");
         $result = $p1->removeStudent("student1_$tenanttoken@$tenanttoken.test.com");
         $this->assertEquals("Success", $result);
        
        
    }

    

}