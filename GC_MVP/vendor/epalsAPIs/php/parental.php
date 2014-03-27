<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("graphmapper.php");
require_once("user.php");


/**
 * Description of teacher
 *
 * @author nehalsyed
 */


class Parental extends User{
    
    private $graphmapper;
    
        /**
        * Constructor for Parental Class
        *
        * @param string $accountId AccountId of user to load. Leave null for new User.
        *
        */
        function __construct($accountId = NULL) {

            parent::__construct($accountId);
            $this->graphmapper = new GraphMapper($this->getTenantDomain());
        }
    
    
       public function setRoles($role) {

              throw new Exception('You cant set Role in this Class. Role is already Set to Parent');
       }

        public function setRawDob($rawDob) {

              throw new Exception('You can\'t set RawDob for Teacher');
       }


        public function setGrade($grade) {

              throw new Exception('You can\'t set Grade for Teacher');
       }

       public function getUserType() {
           return 'Parent';
       }
     
     
        /**
        * Add a student to teacher
        * 
        * @param string studentAccountId Children of parent
        * 
        */
        function addStudent($studentAccountId) {

                $parentAccount = $this->getAccount();

                if(empty($studentAccountId) || empty($parentAccount)){

                    throw new Exception("StudentAccountId or ParentAccountID is not set");

                }

                $student = new Student($studentAccountId);

                return $student->addParent($this->getAccount());

         }
        
     
        /**
        * Remove Student from Teacher(moderator)
        * 
        * @param string studentAccountId Children of parent
        * 
        */
        function removeStudent($studentAccountId) {
            
                $parentAccount = $this->getAccount();
            
                if(empty($studentAccountId) || empty($parentAccount)){

                   throw new Exception("StudentAccountId or ParentAccountID is not set");

                }
                
                $student = new Student($studentAccountId);
            
                return $student->removeParent($this->getAccount());

         }
        
        
        /**
	 * Add  a teacher account to the graph
	 * 
         * 
         * $schoolId string SchoolID (optional). Adds User to default school is SchoolId is NULL
         * 
         * 
         * @return string The user(JSON) that was created
	 */
         function add($schoolId = NULL) {
            
            if(empty($schoolId))
                $schoolId = $this->graphmapper->getDefaultSchool();
            
            parent::setRoles(array('Parent'));
            $result =  parent::add();
            $school = new School($this->getTenantDomain(), $schoolId);
            $school->addUserToSchool($this->getAccount(), $this->getUserType());
            return $result;
         }
        
        
}

?>
