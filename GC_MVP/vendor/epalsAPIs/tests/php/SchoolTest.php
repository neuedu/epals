<?php

require_once('testutility.php');
require_once('../../php/school.php');
require_once('../../php/course.php');
require_once('../../php/graphmapper.php');


/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-05 at 17:49:50.
 */
class SchoolTest extends PHPUnit_Framework_TestCase {

    /**
     * @var School
     */
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    
    /**
     * @covers School::loadSchool
     * @todo   Implement testLoadSchool().
     */
    public function testLoadSchool() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers School::exists
     * @todo   Implement testExists().
     */
    public function testExists() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers School::add
     * @todo   Implement testAdd().
     */
    public function testAdd() {
        
        $tenanttoken = TestUtility::generateRandomString(5);
        
        $school = new School();
        $school->setDescription("$tenanttoken Test Description");
        $school->setName("Test School $tenanttoken");
        $school->setOptionsString("{\"param1\":\"value1\", \"param2\":\"value2\"}");
        $school->setSchoolId("$tenanttoken school 1");
        
    }

    /**
     * @covers School::update
     * @todo   Implement testUpdate().
     */
    public function testUpdate() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers School::addUserToSchool
     * @todo   Implement testAddUserToSchool().
     */
    public function testAddUserToSchool() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}