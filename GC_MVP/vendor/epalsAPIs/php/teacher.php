<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("user.php");
require_once("graphmapper.php");

/**
 * Description of teacher
 *
 * @author nehalsyed
 */
class Teacher extends User{
      
      //put your code here
        private $graphmapper;
    
        
        /**
        * Constructor for Teacher Class
        *
        * @param string $accountId AccountId of user to load. Leave null for new Teacher.
        *
        */
        function __construct($accountId = NULL) {

          parent::__construct($accountId);

          $this->graphmapper = new GraphMapper($this->getTenantDomain());
        }
    
        public function setRoles($role) {

              throw new Exception('You can\'t set Role in Teacher Class. Role is already Set to Teacher');
        }

        public function setRawDob($rawDob) {

              throw new Exception('You can\'t set RawDob for Teacher');
        }


        public function setGrade($grade) {

              throw new Exception('You can\'t set Grade for Teacher');
        }

        public function getUserType() {
           return 'Educator';
        }
     

        /**
        * Add a student to teacher
        * 
        * @param string $studentAccountId add Teachet to Student as moderator
        * 
        * @return string Returns result json 
        */
        function addStudent($studentAccountId) {

            if(!empty($studentAccountId)){

                $account = $this->getAccount();
                //Build the URL of the REST endpoint
                 if (!empty($account))
                 {
                     $json = array(
                        'TenantExternalId' => $this->getTenantDomain(),
                        'StudentAccountId' => $studentAccountId,
                        'ModeratorAccountId' => $this->getAccount()
                    );

                     //Build the URL of the REST endpoint
                     $path = "user/setModerator";

                     //Make the REST call
                     $request = json_encode($json);

                     return parent::_postSISURL($path, null, $request);
                 }
            }
        }
        
        /**
        * detached student from teacher
        * 
        * @param string $studentAccountId studnet accountID to remove from teacher
        */
        function removeStudent($studentAccountId) {
            
            if(!empty($studentAccountId)){
                
                $account = $this->getAccount();
                        
                //Build the URL of the REST endpoint
                 if (!empty($account))
                 {
                     $json = array(
                        'TenantExternalId' => $this->getTenantDomain(),
                        'StudentAccountId' => $studentAccountId,
                        'ModeratorAccountId' => $this->getAccount()
                    );
                     
                     //Build the URL of the REST endpoint
                     $path = "user/removeModerator";

                     //Make the REST call
                     $request = json_encode($json);

                     return parent::_postSISURL($path, null, $request);
                 }
            }
        }
        
       
        
        /**
	 * Add  a teacher account to the graph
	 * Teacher will be added to default School if SchoolId is NULL
         * 
         * @param string $schoolId SchoolID of teacher
         * 
         * @return - The user object that was created
	 */
         function add($schoolId = NULL) {
             
             // add to default school if not mentioned
             if(empty($schoolId))
                 $schoolId = $this->graphmapper->getDefaultSchool();
            
            parent::setRoles(array('Educator'));
            $result = parent::add();
            $school = new School($this->getTenantDomain(), $schoolId);
            $school->addUserToSchool($this->getAccount(), 'Teacher');
            return $result;
        }
        
}

?>
