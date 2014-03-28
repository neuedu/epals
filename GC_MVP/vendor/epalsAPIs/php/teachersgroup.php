<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('section.php');
require_once('graphmapper.php');

/**
 * Description of group
 *
 * @author nehalsyed
 */
class TeachersGroup{
    //put your code here
    
    
    
    private $section;
    private $graphmapper;
    
        /**
        * Construct group object
        * 
        * @param string $tenantDomain Tenant domain
        * @param string $name group name or identifier
        * 
        */
        function __construct($tenantDomain =NULL, $name = NULL) {

            $this->graphmapper = new GraphMapper($tenantDomain);
            $schoolId = $this->graphmapper->getDefaultSchool();
            $courseId = $this->graphmapper->getDefaultCourse();
            $this->section = new Section($tenantDomain, $schoolId, $courseId, $name);
            
        }
    
   
        public function getName() 
        {
            return $this->section->getSectionId();
        }

        public function setName($name) 
        {
            $this->section->setSectionId($name);
        }
        
        public function getTenantDomain() 
        {   
            return $this->section->getTenantDomain();
        }

        public function getSchoolId() {
         return $this->section->getSchoolId();
        }

        public function getCourseId() {
           return $this->section->getCourseId();
        }


        /**
        * add group
        * 
        * @param string $tenantDomain Tenant domain
        * @param string $schoolId Optional: user will be added to default school if not mentioned
        * @param string $courseId Optional: user will be added to default course if not mentioned
        * 
        */
        function add($tenantDomain, $schoolId = NULL, $courseId = NULL)
        {
            if(empty($schoolId))
                $schoolId = $this->graphmapper->getDefaultSchool();
            
            if(empty($courseId))
                $courseId = $this->graphmapper->getDefaultCourse();
            
            return $this->section->add($tenantDomain, $schoolId, $courseId);
        }

        /**
        * add user to group
        * 
        * @param string $userAccountId user account id
        * @param string $userType As user as type (ex: Teacher, Student)
        * 
        * @return string confirmation message
        */
        function addUserToGroup($userAccountId, $userType)
        {
            return $this->section->addSectionEnrollment($userAccountId, $userType);
        }

        /**
        * remove user from group
        * 
        * @param string $userAccountId user account id
        * @param string $userType As user as type (ex: Teacher, Student)
        * 
        * @return string confirmation message
        */
        function removeUserFromGroup($userAccountId, $userType)
        {
            return $this->section->deleteSectionEnrollment($userAccountId, $userType);
        }
        
        /**
        * add teacher to group
        * 
        * @param string $userAccountId user account id
        * 
        * @return string confirmation message
        */
        function addTeacherToGroup($userAccountId)
        {
            return $this->section->addSectionEnrollment($userAccountId, 'Teacher');
        }

        /**
        * remove teacher from group
        * 
        * @param string $userAccountId user account id
        * 
        * @return string confirmation message
        */
        function removeTeacherFromGroup($userAccountId)
        {
            return $this->section->deleteSectionEnrollment($userAccountId, 'Teacher');
        }
    
        /**
        * add student to group
        * 
        * @param string $userAccountId user account id
        * 
        * @return string confirmation message
        */
        function addStudentToGroup($userAccountId)
        {
            return $this->section->addSectionEnrollment($userAccountId, 'Student');
        }

        /**
        * remove student from group
        * 
        * @param string $userAccountId user account id
        * 
        * @return string confirmation message
        */
        function removeStudentFromGroup($userAccountId)
        {
            return $this->section->deleteSectionEnrollment($userAccountId, 'Student');
        }

        
        /**
        * check is user is member of teachers group
        * 
        * @param string $userAccountId user account id
        * 
        * @return boolean true if user is member, else false
        */
        function isMember($userAccountId)
        {
            return $this->section->isEnrolled($userAccountId);
        }
        
        /**
        * Get all members (Student & Teachers) of teachers group
        * 
        * 
        * @return array array of AccountIds
        */
        function getMembers()
        {
            $members =  $this->section->getMembers();
        
            $mem =  array();
            
            foreach ($members as $value) 
            {
                array_push($mem, $value->accountId, NULL);
            }
            
            return $mem;
        }
        
        /**
        * Get all Teachers in teachers group
        * 
        * 
        * @return array array of AccountIds
        */
        function getTeachers()
        {
            $members =  $this->section->getMembers();
            
            $mem =  array();
            
            foreach ($members as $value) 
            {
                if(in_array('Educator', $value->roles)){
                    array_push($mem, $value->accountId);
                }
            }
            
            return $mem;
        }
        
        
        /**
        * Get all Students in teachers group
        * 
        * 
        * @return array array of AccountIds
        */
        function getStudents()
        {
            $members =  $this->section->getMembers();
        
            $mem =  array();
            
            foreach ($members as $value) 
            {
                if(in_array('Student', $value->roles)){
                    array_push($mem, $value->accountId, NULL);
                }
            }
            
            return $mem;
        }
    
}

?>
