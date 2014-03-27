<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('config.php');
require_once('classes/rest.php');
require_once('graphmapper.php');

/**
 * Description of group
 *
 * @author nehalsyed
 */
class Group extends Rest{
    //put your code here
    
        private $id; //uuid
        private $name;
        private $description;
        private $externalId;
        private $status; // possible values  [Completed, InProgress, Active, Disabled]:
        private $tenantDomain;

        private $graphMapper;

        // Status values
        public static $Status_Completed='Completed';
        public static $Status_InProgress='InProgress';
        public static $Status_Active='Active';
        public static $Status_Disabled='Disabled';
                
        
        // Group role
        public static $GroupRole_Owner='Owner';
        public static $GroupRole_Member='Member';
        public static $GroupRole_Observer='Observer';
        public static $GroupRole_Disabled='Assistant';
        
        /**
	* Constructor for Creating Group object
        * 
        * Object can be created using $groupuuid 
        * 
        * @param string $groupuuid Group UUID 
        *   
        * @return void
        */
        function __construct($groupuuid = NULL) {
            $this->graphMapper = new GraphMapper();
            
            if(isset($groupuuid))
            {
                $this->loadGroup($groupuuid);
            }
            
        }

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getExternalId() {
            return $this->externalId;
        }

        public function getStatus() {
            
            return $this->status;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function setExternalId($externalId) {
            $this->externalId = $externalId;
        }

        public function setStatus($status) {
            
            if($status != self::$Status_Active && $status != self::$Status_Completed
                    && $status != self::$Status_Disabled && $status != self::$Status_InProgress )
                throw new Exception('Status provided is now allowed. Please $Status_* static property to set allowed status');
            
            
            $this->status = $status;
        }

        public function getTenantDomain() {
            return $this->tenantDomain;
        }
        
        
        /**
	 * load Group class properties
         * 
         * @param string $groupuuid Group UUID
         * 
         * @return void
	 */
        function loadGroup($groupuuid)
        {
            if(!isset($groupuuid))
                throw Exception("groupuuid parameter is empty");
            
             $path = "/group/".rawurlencode($groupuuid);
             $group = parent::_getSISURL($path, NULL);
             
          //   if(!isset($group->getGroupByIdModule))
           //      throw new Exception("Unable to Find Group. Group may be disabled or not exist");
             
             $this->sisJSONToObject($group);
        }
    
    
    
        /**
	 * Add a group to as follows:
         * 
         * if $parentId is provided than group is added under parent
         * if only $schoolId is provided: group added to school
         * if only $teachersGroupName is provided: group is added to teachers group in default school
         * if $schoolId and $teachersGroupName is provided: group is added to specified teachers group in specifed school
         * if neither of $schoolId and $teachersGroupName is provided: group is added to tenant directly
         * 
         * @param string $tenantDomain tenant domain 
         * @param string $schoolId school id 
         * @param string $teachersGroupName teacher group name
         * @param uuid $parentId Parent UUID
         * @param string $permission_role permission defines who can access child group. Valid values are: (Owner, Assistant, Member, Observer). 
         *       
         *       if $permission_role = Owner: only Owner can access child group
         *       if $permission_role = Assistant: Owner and Assistant can access child group
         *       if $permission_role = Member: Owner, Assistant and Member can access child group
         *       if $permission_role = Observer: Owner, Assistant and Member, Observer can access child group
         * 
         * @return - uuid of group created
         *         - parentuuid if parentID is provided in param
	 */
        function add($tenantDomain=NULL, $schoolId = NULL, $teachersGroupName = NULL, $parentId = NULL, $permission_role=NULL) {
           
            if(!isset($tenantDomain) && !isset($parentId))
                throw new Exception("Either tenantDomain or parentId is required");
           
            if(!isset($this->status))
            {
             //     throw  new Exception("Status[Completed, InProgress, Active, Disabled] not set");
                $this->status = self::$Status_InProgress;
            }
            if(!isset($this->name))
                  throw  new Exception("Name not set");
            
            if(isset($permission_role))
            {
                
                if(!isset($parentId))
                    throw  new Exception("permission_role must be proved with parent group uuid");
                
                if($permission_role != self::$GroupRole_Disabled && $permission_role != self::$GroupRole_Member
                        && $permission_role != self::$GroupRole_Observer && $permission_role != self::$GroupRole_Owner)
                    throw  new Exception('$permission_role parameter value is not value. Please use $GroupRole_* static property to get allowed values.');
                
            }
            if(isset($parentId) && isset($permission_role))
                    $path = "group/" . rawurlencode($parentId)."/createSubGroup/". rawurlencode($permission_role);
            else if(isset($parentId) && !isset($permission))
                    $path = "group/" . rawurlencode($parentId)."/createSubGroup";
            else if(isset($schoolId) && isset($teachersGroupName))
                    $path = "group/" . rawurlencode($tenantDomain) . "/school/".rawurlencode($schoolId) . "/course/".rawurlencode($this->graphMapper->getDefaultCourse())."/section/".rawurlencode($teachersGroupName)."/create";
            else if(isset($teachersGroupName) && !isset($schoolId))
                    $path = "group/" . rawurlencode($tenantDomain) . "/school/".rawurlencode($this->graphMapper->getDefaultSchool()) . "/course/".rawurlencode($this->graphMapper->getDefaultCourse())."/section/".rawurlencode($teachersGroupName)."/create";
            else if(isset($schoolId))
                    $path = "group/" . rawurlencode($tenantDomain) . "/school/".rawurlencode($schoolId) ."/create";
            else
                $path = "group/" . rawurlencode($tenantDomain) . "/create";
            
            $groupArray = array (
                'Name' => $this->name,
                'Description' => $this->description,
                'ExternalId' => $this->externalId,
                'Status' => $this->status
                );
            
            $response = parent::_postSISURL($path, null, json_encode($groupArray));
            
            if(isset($response->Id)){
                
                $this->id = $response->Id;
                $this->tenantDomain = $tenantDomain;
                return $response->Id;
            }
             
            return $response;
            
        }
        
        
        
        /**
	 * update group
         * 
         * @return - Object of updated group
	 */
        function update() {
           
            if(!isset($this->id))
                throw new Exception("Id is not set. Please load Group via Constructor");
           
            if(!isset($this->status))
                  throw  new Exception("Status[Completed, InProgress] not set");
            
            if(!isset($this->name))
                  throw  new Exception("Name is not set");
            
             $getpath = "/group/".rawurlencode($this->id);
             
             $group = parent::_getSISURL($getpath, NULL);
            
             $ugroup = $this->updateGroup($group);
            
             $path = "group/update";
            
             $response = parent::_putSISURL($path, null, json_encode($ugroup));
            
            return $response;
            
        }
        
       
        /**
	 * Set group status to disabled
         * 
         * @return - true
	 */
        function delete() {
           
             if(!isset($this->id))
                throw new Exception("Id is not set. Please load Group via Constructor");
             
                $path = "group/update";
            
                $group = array();
                
                $group['Status'] = Group::$Status_Disabled;
                
                $group['Id'] = $this->id;     
                
                $response = parent::_putSISURL($path, null, json_encode($group));
            
            return $response;
            
        }
        
        
        /**
        * get Members of group
        * 
        * 
        * @return array array contains account of members
        */ 
        function getMembers(){
           
           
           if(!isset($this->id))
                 throw new Exception("Group UUID not set. Please load Group details via constructor");
             
             $path = "/group/".rawurlencode($this->id);
             
             $sections = parent::_getSISURL($path, NULL);
             
             return $sections->Members;
           
       }
       
       
       
        /**
        * get all Users in the Group
        * 
        * 
        * @return array array contains user and grouprole of members
        */ 
        function getGroupUsers(){
           
           $members = array();
            
           if(!isset($this->id))
                 throw new Exception("Group UUID not set. Please load Group details via constructor");
             
             $path = "accessmanager/getGroupUsers";
             
             $param = "groupid=".rawurlencode($this->id);
             
             $userjson = parent::_getPMURL($path, $param);
             
             $users = $userjson->GroupUsers[0]->GroupUsers;
             
             foreach($users as $usr)
             {   
               $d = array('AccountId' => $usr->accountId ,'GroupRole' => $usr->groupRole);
               array_push($members, $d);
             }
             
            // $members = asort($members);
             
             return $members;
           
       }
       
       
       /**
        * get Observers member of group
        * 
        * 
        * @return array array contains account of observers
        */ 
        function getObservers(){
           
           
           if(!isset($this->id))
                 throw new Exception("Group UUID not set. Please load Group details via constructor");
             
             $path = "/group/".rawurlencode($this->id);
             
             $sections = parent::_getSISURL($path, NULL);
             
             return $sections->Observers;
           
       }
       
       
       /**
        * get Assistants of owner in group
        * 
        * 
        * @return array array contains account of hosts
        */
       function getAssistants(){
           
           
           if(!isset($this->id))
                 throw new Exception("Group UUID not set. Please load Group details via constructor");
             
             $path = "/group/".rawurlencode($this->id);
             
             $sections = parent::_getSISURL($path, NULL);
             
             return $sections->Assistants;
           
       }
        
       
       
       /**
        * get Owners of group
        * 
        * 
        * @return array array contains account of owners
        */
       function getOwners(){
           
           
           if(!isset($this->id))
                 throw new Exception("Group UUID not set. Please load Group details via constructor");
             
             $path = "/group/".rawurlencode($this->id);
             
             $sections = parent::_getSISURL($path, NULL);
             
             return $sections->Owners;
           
       }
       
       
        /**
        * add User as Assitant in Group
        * 
        * @param string $userAccountId user account id
        * 
        * @return object Group Object with list of hosts and Exception in case of error
        */
        function addAssistant($userAccountId)
        {
          if(!isset($userAccountId))
              throw new Exception("userAccountID parameter is not set");
          
          if(!isset($this->id))
              throw new Exception("Project UUID not Set. Please load Project via constructor");
            
            $path = "group/" . rawurlencode($this->id)."/addAssistant";
            $param = "accountId=".rawurlencode($userAccountId);
            
            
            $response = parent::_postSISURL($path, $param);
            
            return $response;
        }
        
        /**
        * add User as Member of Group
        * 
        * @param string $userAccountId user account id
        * 
        * @return object Group Object with list of hosts and Exception in case of error
        */
        function addMember($userAccountId)
        {
          if(!isset($userAccountId))
              throw new Exception("userAccountID parameter is not set");
          
          if(!isset($this->id))
              throw new Exception("Project UUID not Set. Please load Project via constructor");
            
            $path = "group/" . rawurlencode($this->id)."/addMember";
            $param = "accountId=$userAccountId";
            
            
            $response = parent::_postSISURL($path, $param);
            
            return $response;
        }
    
        
        /**
        * add User as Observer of Group
        * 
        * @param string $userAccountId user account id
        * 
        * @return object Group Object with list of observers and Exception in case of error
        */
        function addObserver($userAccountId)
        {
          if(!isset($userAccountId))
              throw new Exception("userAccountID parameter is not set");
          
          if(!isset($this->id))
              throw new Exception("Group UUID not Set. Please load Project via constructor");
            
            $path = "group/" . rawurlencode($this->id)."/addObserver";
            $param = "accountId=$userAccountId";
            
            
            $response = parent::_postSISURL($path, $param);
            
            return $response;
        }
        
        
         /**
        * add User as Owner of Group
        * 
        * @param string $userAccountId user account id
        * 
        * @return object Group Object with list of observers and Exception in case of error
        */
        function addOwner($userAccountId)
        {
          if(!isset($userAccountId))
              throw new Exception("userAccountID parameter is not set");
          
          if(!isset($this->id))
              throw new Exception("Group UUID not Set. Please load Project via constructor");
            
            $path = "group/" . rawurlencode($this->id)."/addOwner";
            $param = "accountId=$userAccountId";
            
            
            $response = parent::_postSISURL($path, $param);
            
            return $response;
        }
        
        
        
        /**
        * Remove user membership from group
        * 
        * @param string $userAccountId user account id
        * 
        * @return object Group Object with list of observers and Exception in case of error
        */
        function removeMembership($userAccountId)
        {
          if(!isset($userAccountId))
              throw new Exception("userAccountID parameter is not set");
          
          if(!isset($this->id))
              throw new Exception("Project UUID not Set. Please load Project via constructor");
            
            $path = "group/" . rawurlencode($this->id)."/remove";
            $param = "accountId=$userAccountId";
            
            $response = parent::_postSISURL($path, $param);
            
            return $response;
        }
        
        
        
        /**
	 * Retrive attributes from SIS-REST Group json object and set properties of this class
	 *
	 * @param string $groupJSON - JSON group object from SIS-REST
	 * 
         */
         private function sisJSONToObject($groupJSON){
             
            
            $this->name = $groupJSON->Name;
            $this->id = $groupJSON->Id;
            $this->status = $groupJSON->Status;
            
            if(isset($groupJSON->Description))
                $this->description = $groupJSON->Description;
           
            if(isset($groupJSON->ExternalId))
                $this->externalId = $groupJSON->ExternalId;
         }
         
         
        /**
        * check if user is Member of Group
        * 
        * 
        * @return boolean true if member is in group
        */ 
        function isMember($accountId){
           
           
             $members = $this->getMembers();
             
             return in_array(strtolower($accountId), $members);
           
        }
        
        /**
        * check if user is Assistant in Group
        * 
        * 
        * @return boolean true if member is in group
        */ 
        function isAssistant($accountId){
           
           
             $members = $this->getAssistants();
             
             return in_array(strtolower($accountId), $members);
           
        }
       
        
        /**
        * check if user is Observer of Group
        * 
        * 
        * @return boolean true if observer is in group
        */ 
        function isObserver($accountId){
           
           
             $members = $this->getObservers();
             
             return in_array(strtolower($accountId), $members);
           
        }
        
        
        /**
        * check if user is Owner of Group
        * 
        * 
        * @return boolean true if owner is in group
        */ 
        function isOwner($accountId){
           
           
             $members = $this->getOwners();
             
             return in_array(strtolower($accountId), $members);
           
        }
        
        
        
        private function updateGroup($group)
         {
             
            if(!is_null($this->description))
                $group->Description = $this->getDescription();
            
            if(!is_null($this->externalId))
                $group->ExternalId = $this->getExternalId();
            
            if(!is_null($this->id))
                $group->Id = $this->getId();
             
            if(!is_null($this->name))
                $group->Name = $this->getName();
             
            if(!is_null($this->status))
                $group->Status = $this->getStatus();
            
            if(isset($group->NodeId))
                unset($group->NodeId);
            
            if(isset($group->NodeName))
                unset($group->NodeName);
           
            
             return $group;
         }
         
         
         
         
         /**
	 *  Create Group object (JSON) from class properties
	 *
	 * @return string Group object in JSON
	 * 
         */
        private function getJsonTypeArray()
        {
            $group = array();
                    
            if(isset($this->description))
                $group['Description'] = $this->getDescription();
            
            if(isset($this->externalId))
                $group['ExternalId'] = $this->getExternalId();
            
            if(isset($this->id))
                $group['Id'] = $this->id;
            
            if(isset($this->name))
                $group['Name'] = $this->getName();
            
            if(isset($this->status))
                 $group['Status'] = $this->getStatus();
            
            return $group;
        }
         
}

?>
