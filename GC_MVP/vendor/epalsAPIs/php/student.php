<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("graphmapper.php");
require_once("user.php");

/**
 * Description of student
 *
 * @author nehalsyed
 */
class Student extends User{
        //put your code here
        private $graphmapper;
        
        
        /**
        * Constructor for Student Class
        *
        * @param string $accountId AccountId of user to load. Leave null for new User.
        *
        */
        function __construct($accountId = NULL) {

            parent::__construct($accountId);
            $this->graphmapper = new GraphMapper($this->getTenantDomain());
        }


         public function setRoles($role) {

                throw new Exception('You cant set Role in Student Class. Role is already Set to Student');
         }

         public function getUserType() {
             
             return 'Student';
         }
     
    
        /**
        * Add specified moderator/teacher to student
        *
        * @param $moderatorAccountId - Account Id of the teacher/moderator
        * 
        * @return string Returns relationship object if sucessfull
        */
        function addModerator($moderatorAccountId) {

            if(!empty($moderatorAccountId)){

                $account = $this->getAccount();
                
                //Build the URL of the REST endpoint
                 if (!empty($account))
                 {
                     $json = array(
                        'TenantExternalId' => $this->getTenantDomain(),
                        'StudentAccountId' => $this->getAccount(),
                        'ModeratorAccountId' => $moderatorAccountId
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
	 * Remove specified moderator/teacher from student
	 *
	 * @param string $moderatorAccountId - Account Id of the teacher/moderator
         * 
         * @return string
	 */
         function removeModerator($moderatorAccountId) {
            
            if(!empty($moderatorAccountId)){
                
                $account = $this->getAccount();
                
                //Build the URL of the REST endpoint
                 if (isset($account))
                 {
                     $json = array(
                        'TenantExternalId' => $this->getTenantDomain(),
                        'StudentAccountId' => $this->getAccount(),
                        'ModeratorAccountId' => $moderatorAccountId
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
        * Add specified mentor to student
        *
        * @param $mentorAccountId - Account Id of the mentor
        * 
        * @return string returns relationship object if successfull
        */
        function addMentor($mentorAccountId) {

            if(!empty($mentorAccountId)){
                
                $mentor = new User($mentorAccountId);
                
                $mentorInternalID = $mentor->getInternalId();
                
                if(empty($mentorInternalID))
                        throw new Exception("Unable to find mentor: $mentorAccountId"); 

                $account = $this->getAccount();
                
                //Build the URL of the REST endpoint
                 if (!empty($account))
                 {
                     $json = array(
                        'TenantExternalId' => $this->getTenantDomain(),
                        'StudentAccountId' => $this->getAccount(),
                        'MentorExternalId' => $mentor->getUserId()
                    );

                     //Build the URL of the REST endpoint
                     $path = "user/setMentor";

                     //Make the REST call
                     $request = json_encode($json);

                     return parent::_postSISURL($path, null, $request);
                 }
            }
         }
        
        /**
	 * Remove specified mentor from student
	 *
	 * @param string $mentorAccountId - Account Id of the teacher/moderator
         * 
         * @return string Sucess on Success
	 */
         function removeMentor($mentorAccountId) {
            
            if(!empty($mentorAccountId)){
                
                $mentor = new User($mentorAccountId);
                $mentorInternalId = $mentor->getInternalId();
                
                if(empty($mentorInternalId))
                        throw new Exception("Unable to find mentor: $mentorAccountId"); 

                $account = $this->getAccount();
                
                //Build the URL of the REST endpoint
                 if (isset($account))
                 {
                     $json = array(
                        'TenantExternalId' => $this->getTenantDomain(),
                        'StudentAccountId' => $this->getAccount(),
                        'MentorExternalId' => $mentor->getUserId()
                    );
                     
                     //Build the URL of the REST endpoint
                     $path = "user/removeMentor";

                     //Make the REST call
                     $request = json_encode($json);

                     return parent::_postSISURL($path, null, $request);
                 }
            }
         }
        

         
         
         
        
         /**
	 * Add a parent to a student
	 *
	 * @param string $parentAccountId - Account Id of the parent
	 */
         function addParent($parentAccountId){
        
            if(!empty($parentAccountId))
            {
               //Build the URL of the REST endpoint
               $path = "user/addParent";

                       //Make the REST call
                   $user = array(
                    'ParentAccountId' => $parentAccountId,
                    'StudentAccountId' => $this->getAccount()
               );
               return parent::_postSISURL($path, null, json_encode($user));
            }
                
         }

         /**
	 * Remove a parent from a student
	 *
	 * @param $parentAccountId - Account Id of the parent
	 */
         function removeParent($parentAccountId){
           
            //Build the URL of the REST endpoint
            $path = "user/removeParent";

		//Make the REST call
            $user = array(
                 'ParentAccountId' => $parentAccountId,
                 'StudentAccountId' => $this->getAccount()
            );
		return parent::_postSISURL($path, null, json_encode($user));
        }
       
        /**
	 * Add a student account to the graph
         * if schoolId is NULL  user is added to default school
	 * 
         * @return - The user(JSON) that was created
	 */
        function add($schoolId = NULL) {
            
            if(empty($schoolId))
                $schoolId = $this->graphmapper->getDefaultSchool();
            
            parent::setRoles(array('Student'));
            $result = parent::add();
            $school = new School($this->getTenantDomain(), $schoolId);
            $school->addUserToSchool($this->getAccount(), $this->getUserType());
            return $result;
        }
}

?>
