<?php

/*
 * Copyright ePals, Inc.
 * 
 * User class that interacts with the SIS & Policy Manager rest apis. 
 * 
 */

require_once('classes/rest.php');
require_once('utils.php');


class User extends Rest {
	
        private $account; //required: user account id in format [username]@[tenant_Domain]
        private $ePalsEmail; // required: user email in format [username]@[tenant_EmailDomain]
        private $externalEmail; // user external email address ex: nsyed@mac.com
	private $userId; // user external id
	private $firstName; //required: user first or given name
        private $grade; // required: grade in case of student
	private $lastName; //required: user last or sur name
	private $rawDob; // student date of birth in format yyyymmdd ex: 19960101
	private $roles; //req - (will retired) use role in system. possible values are 1. Student 2. Educator 3. DistrictAdmin 4. Parent
        private $tenantDomain; // tenant domain 
        private $internalId; // internal UUID
        private $disabled = false; 
        private $userMetaData =''; // exteral field to hold extended data
        private $encryptedPasword; // user encrypted password
        private $password;
        
        
        
        /**
        * Constructor for User Class
        *
        * @param string $accountId AccountId of user to load. Leave null for new User.
        *
        */
        function __construct($accountId = NULL) {
            
            
            if(!empty($accountId)){
            
                if(!check_email_address($accountId))
                     throw new Exception("Please use Account ID in email format username@domain.com");
             
                 $domain = substr(strrchr($accountId, "@"), 1);
                 $this->tenantDomain = $domain;
                 try {
                     $this->loadUser($accountId);
                 } catch (Exception $e) {
                     error_log("user contrunctor: $e");
                     return;
                 }
            }
            
        }
        
        /**
        * @return string user account id in format [username]@[tenant_Domain]
        */
        public function getAccount() {
            return $this->account;
        }

        /**
        * @param string user account id in format [username]@[tenant_Domain]
        */
        public function setAccount($account) {
            
            if(!check_email_address($account))
                 throw new Exception("Please set Account ID in email format: username@domain.com");
               
            $this->account = $account;
        }

        /**
        * @return string user email in format [username]@[tenant_EmailDomain]
        */
        public function getEPalsEmail() {
            return $this->ePalsEmail;
        }

        
        /**
        * @param string $ePalsEmail user email in format [username]@[tenant_EmailDomain]
        */
        public function setEPalsEmail($ePalsEmail) {
            
             if(!check_email_address($ePalsEmail))
                    throw new Exception("Please use ePalsEmail in email format: username@emaildomain.com");
            
            $this->ePalsEmail = $ePalsEmail;
        }

        /**
        * @return string user external email address ex: nsyed@mac.com
        */
        public function getExternalEmail() {
            return $this->externalEmail;
        }

        /**
        * @param string $externalEmail user external email address ex: nsyed@mac.com
        */
        public function setExternalEmail($externalEmail) {
            
            if(!check_email_address($externalEmail))
                  throw new Exception("Please use externalEmail in email format username@externalemaildomain.com");
             
            $this->externalEmail = $externalEmail;
        }

        /**
        * @return string userId (externalID)
        */
        public function getUserId() {
            return $this->userId;
        }

        /**
        * @param string $userId userId (external id)
        */
        public function setUserId($userId) {
            $this->userId = $userId;
        }

        /**
        * @return string user first name
        */
        public function getFirstName() {
            return $this->firstName;
        }

        /**
        * @param string $firstName user first name
        */
        public function setFirstName($firstName) {
            $this->firstName = $firstName;
        }

        /**
        * @return string user grade
        */
        public function getGrade() {
            return $this->grade;
        }

        /**
        * @param string $grade user grade
        */
        public function setGrade($grade) {
            $this->grade = $grade;
        }

        /**
        * @return string $lastName
        */
        public function getLastName() {
            return $this->lastName;
        }

        /**
        * @param string $lastName user last name
        */
        public function setLastName($lastName) {
            $this->lastName = $lastName;
        }

        /**
        * @return string User date of birth in format yyyymmdd ex: 19960101
        */
        public function getRawDob() {
            return $this->rawDob;
        }

        /**
        * @param string $rawDob User date of birth in format yyyymmdd ex: 19960101
        */
        public function setRawDob($rawDob) {
            $this->rawDob = $rawDob;
        }

        /**
        * @return string user Roles Array
        */
        public function getRoles() {
            return $this->roles;
        }

        /**
        * @param mixed[] $role Array of user Roles 
        */
        public function setRoles($role) {
            $this->roles = $role;
        }
        
        /**
        * @return string user account domain
        */
        public function getTenantDomain() {
            
              if(!empty($this->account) && strrpos($this->account, '@') > 0){
              
                  return substr(strrchr($this->account, "@"), 1);
              }
            return $this->tenantDomain;
        }

        
        
        /**
        * @return UUID user interual id
        */
        public function getInternalId() {
            return $this->internalId;
        }

        
        /**
        * @return bool disabled flag
        */
        public function isDeleted() {
            return $this->disabled;
        }
        
        
        /**
        * @return string user extended properties in json format
        */
        public function getUserMetaData() {
            return $this->userMetaData;
        }

        
        /**
        * @param string $userMetaData user extended properties in json format
        */
        public function setUserMetaData($userMetaData) {
            $this->userMetaData = $userMetaData;
        }
        
        /**
        * @return string Encrypted Password
        */
        public function getEncryptedPasword() {
            return $this->encryptedPasword;
        }

        /**
        * @param string $password plaintext Password
        */
        public function setPassword($password) {
            $this->password = $password;
        }

       
	
        /**
         * Verify user password
         * 
         * @param $verifypassword : user password to verify
         * 
         * @return 'true' - if password match else 'false'
         */
         function verifyPassword($verifypassword) 
         {      
            if(empty($this->account))
            {
                throw new Exception("Account property is empty. Please load user!");
            }

            if(empty($verifypassword))
            {
                throw new Exception("Password provided is empty.");
            }

            $path = "/accessmanager/validateUserPassword";

            $params = "userid=".rawurlencode($this->account)."&password=".rawurlencode($verifypassword);

            $result = parent::_getPMURL($path, $params);

            return $result->validateUserPasswordModule[0]->UserPasswordCheck;

         }

        /**
        * Check if user exist in database
        * 
        * @param $email : accountId or email address or ExternalEmail of user
        * 
        * @return 'true' - if username match else 'false'
        */
        public static function userExists($email) {
               if(empty($email)){
                   throw new Exception('email procided is empty!');
               } 
               $r = new Rest();
               $res = FALSE;
               $path = "/accessmanager/getUser";
               $params = "email=".rawurlencode($email);
               try {
                   $user = $r->_getPMURL($path, $params);
               } catch (Exception $e) {
                   error_log("userExists: $e");
                   return FALSE;
               }
               if (!empty($user->getUser[0]->user->accountId)) {
                   $res = TRUE;
               }
               return $res;
         }
        
     
        
        /**
         * load user info in class
         *
         * @param $accountId - user email format AccountId (this is not user email address)
         *
         * @return - sets User object 
         */
          function loadUser($account){
              

              if(empty($account))
                    throw new Exception("Account provided is empty");
              
             $usr = null;
             if(!check_email_address($account))
                        throw new Exception("Please provide account in email format: username@domain.com");
           
                    $path = "user/account/".rawurlencode($account);
                    try {
                        $usr = parent::_getSISURL($path, NULL);
                    } catch (Exception $e) {
                        error_log("loadUser: $e");
                        throw new Exception($e->getMessage());
                    }
                    $this->sisJSONToObject($usr);
          }
        
        
        /**
        * Update properties of a user account
        * important: load user object before call update method to avoid overriding properties
        *
        */
        function update(){

            if( !isset($this->roles) || empty($this->roles) || !isset($this->roles[0]) || empty($this->roles[0]))
                  throw new Exception ("User Role is not set!");
            
            if(!isset($this->account))
                  throw  new Exception("Account is not set. Please load Object via Constructor");
           
               //Build the URL of the REST endpoint
               $getpath = "user/account/" . rawurlencode($this->getAccount());  //Current bug in SIS requires extra .com

               $user = parent::_getSISURL($getpath, NULL);
             
               $user = $this->updateUser($user);
               
               //Build the URL of the REST endpoint
               $tmpRoles = $this->getRoles();
               $updatepath = "user/" . rawurlencode($tmpRoles[0]) . "/edit";

               
               $result = parent::_postSISURL($updatepath, null, json_encode($user));
           
               if($result->NodeId > 0 && !empty($this->password))
                   $this->updatePassword ($this->password);
               
               return $result;
        }
        

     
        /**
        * Delete user account
        * Deleted User account is retrievable with retrive method
        * 
        * @return User Object
        */
        function delete(){
            
            if(!isset($this->account))
                  throw  new Exception("Account is not set. Please load Object via Constructor");
            
            $user = new User($this->account);
            $user->disabled = true;
            $result =  $user->update();
            $this->disabled = true;
            return $result;
        }

        
        /**
        * Retrive deleted user account
        *
        * @return User Object
        */
        function retrive(){
            
            if(!isset($this->account))
                  throw  new Exception("Account is not set. Please load Object via Constructor");
           
            $user = new User($this->account);
            $user->disabled = false;
            $result =  $user->update();
            $this->disabled = false;
            return $result;
        }
        
    
        /**
	 * Add a user account to the graph database
         * 
         * mandatory properties to set: 
         * 1. account
         * 2. ePalsEmail
         * 3. externalEmail
         * 4. firstName
         * 5. lastName
         * 6. grade (only in case of Student)
	 * 
         * @return - The user object that was created
	 */
        function add() {
            
            if( !isset($this->roles) || empty($this->roles) || !isset($this->roles[0]) || empty($this->roles[0]) )
                throw new Exception ("User Role is not set!");
            
            if(!isset($this->account))
                  throw  new Exception("Account is not set");
            if(!isset($this->password))
                  throw  new Exception("Password is not set");
            if(!isset($this->firstName))
                  throw  new Exception("FirstName is not set");
            if(!isset($this->lastName))
                  throw  new Exception("LastName is not set");
            
            
            $path = "user/" . rawurlencode($this->roles[0]) . "/create";
            
            $userArray = array (
                'ExternalId' => $this->userId,
                'FirstName' => $this->firstName,
                'LastName' => $this->lastName,
                'Rawdob' => $this->rawDob,
                'ExternalEmail' => $this->externalEmail,
                'EPalsEmail' => $this->ePalsEmail,
                'Account' => $this->account,
                'Grade' => $this->grade,
                'Roles' => $this->roles,
                'Password' => $this->password,
                'Disabled' => $this->disabled,
                'OptionsString' => $this->userMetaData
                    
                );
            
            $response = parent::_postSISURL($path, null, json_encode($userArray));
            return $response;
        }
        
        
        /**
	 * Update password of given user account
         * 
         * @param string $accountId User accountId
         * 
         * @param string $newpassword User plaintext password
	 * 
         * @return - "Success" or "Failure" string
	 */
         public function updatePassword($newpassword) {

           if(!isset($this->account))
                throw new Exception ("User AccountID not set");
             
           if(!isset($newpassword))
                throw new Exception ("NewPassword not provided");
             
           
            $path = "user/" . rawurlencode($this->account) . "/setPassword";

            $response = parent::_putSISURL($path, null, $newpassword);
            
            if($response == 'Success')
                $this->setPassword ($newpassword);
            
            return $response;
         }
        
         /**
	 *  Get Group of user
	 *
	 * @param $userJSON - User object from SIS-REST
	 * 
         */
         function getGroups()
         {
             
             if(!isset($this->account))
                 throw new Exception("AccountId not set!");
             
             $path = "/accessmanager/getGroups";
             //TODO: $param = "account=".rawurlencode($this->account);
             $param = "email=".rawurlencode($this->ePalsEmail);
             $result = parent::_getPMURL($path, $param);
             $groups = $result->getGroupsModule[0]->Groups;
             
             return $groups;
         }
         
         /**
	 *  Get all teacher groups user belong to
	 *
	 * @return array list of teachersgroup name
	 * 
         */
         function getTeacherGroups()
         {
             
             if(!isset($this->account))
                 throw new Exception("AccountId not set!");
             
             $path = "/accessmanager/getClasses";
             $param = "account=".rawurlencode($this->account);
             
             $sections = parent::_getPMURL($path, $param);
             $classes = $sections->getClassesModule;
             
             $teachergroups = array();
             
             foreach($classes as $cls){
                 
                array_push($teachergroups, $cls->Object->{'course.ExternalId'});
             }
             
             return $teachergroups;
         }
         
         
         private function updateUser($user)
         {
             
             if(!is_null($this->account))
                $user->Account = $this->getAccount();
            
            if(!is_null($this->ePalsEmail))
                $user->EPalsEmail = $this->getEPalsEmail();
            
            if(!is_null($this->userId))
                $user->ExternalId = $this->getUserId();
            
            if(!is_null($this->firstName))
                $user->FirstName = $this->getFirstName();
            
            if(!is_null($this->lastName))
                 $user->LastName = $this->getLastName();
            
            if(!is_null($this->rawDob))
                 $user->Rawdob = $this->getRawDob();
            
            if(!is_null($this->encryptedPasword))
                 $user->Password = $this->encryptedPasword;
            
            if(!is_null($this->externalEmail))
                 $user->ExternalEmail = $this->getExternalEmail();
            
            if(!is_null($this->grade))
                $user->Grade =  $this->getGrade();
            
            if(isset($this->disabled))
                $user->Disabled = $this->isDeleted();
            
            if(!is_null($this->roles))
                $user->Roles =  $this->getRoles();
            
            if(!is_null($this->userMetaData))
               $user->OptionsString = $this->getUserMetaData();
            
            if(isset($user->NodeId))
                unset($user->NodeId);
            
            if(isset($user->Id))
                unset($user->Id);
            
            if(isset($user->NodeName))
                unset($user->NodeName);
             
             return $user;
         }
        
        /**
	 *  Retrive attributes from SIS-REST User json object and set properties of this class
	 *
	 * @param $userJSON - User object from SIS-REST
	 * 
         */
        private function sisJSONToObject($userJSON){
             
            $this->setAccount($userJSON->Account);
            
            if($userJSON->EPalsEmail)
                $this->setEPalsEmail($userJSON->EPalsEmail);
            
            if($userJSON->ExternalEmail)
                $this->setExternalEmail($userJSON->ExternalEmail);
            
            if($userJSON->FirstName)
                $this->setFirstName($userJSON->FirstName);
            
            if($userJSON->LastName)
                $this->setLastName($userJSON->LastName);
            
            $this->internalId = $userJSON->Id;
            
            if($userJSON->Roles)
                $this->roles = $userJSON->Roles;
            
            if($userJSON->Disabled)
                $this->disabled = $userJSON->Disabled;
            
            if($userJSON->OptionsString)
                $this->setUserMetaData($userJSON->OptionsString);
          
            if($userJSON->ExternalId)
                $this->setUserId($userJSON->ExternalId);
           
            if(isset($userJSON->Grade))
                $this->grade = $userJSON->Grade;
            
            if($userJSON->Password)
                $this->encryptedPasword = $userJSON->Password;
          
            if(isset($userJSON->Rawdob))
                $this->rawDob = $userJSON->Rawdob;
           
        }
        
        
        /**
	 *  Create User objet (JSON) from class properties
	 *
	 * @return string User object in JSON
	 * 
         */
        /*
        private function getJsonTypeArray()
        {
            $section = array();
                    
            if(isset($this->account))
                $section['Account'] = $this->getAccount();
            
            if(isset($this->ePalsEmail))
                $section['EPalsEmail'] = $this->getEPalsEmail();
            
            if(isset($this->userId))
                $section['ExternalId'] = $this->getUserId();
            
            if(isset($this->firstName))
                $section['FirstName'] = $this->getFirstName();
            
            if(isset($this->lastName))
                 $section['LastName'] = $this->getLastName();
            
            if(isset($this->rawDob))
                 $section['Rawdob'] = $this->getRawDob();
            
            if(isset($this->encryptedPasword))
                 $section['Password'] = $this->encryptedPasword;
            
            if(isset($this->externalEmail))
                 $section['ExternalEmail'] = $this->getExternalEmail();
            
            if(isset($this->grade))
                $section['Grade'] =  $this->getGrade();
            
            if(isset($this->disabled))
                $section['Disabled'] = $this->isDeleted();
            
            if(isset($this->roles))
                $section['Roles'] =  $this->getRoles();
            
            if(isset($this->userMetaData))
               $section['OptionsString'] = $this->getUserMetaData();
            
           return $section;
            
        }
	*/
        
        
        
}


?>
