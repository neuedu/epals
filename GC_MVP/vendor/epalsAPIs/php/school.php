<?
/**
 * This is part of a Demo PHP application for demonstrating the ePals Webservice APIs
 * 
 * 
 * @copyright ePals Inc, all rights reserved
 */

require_once('config.php');
require_once('classes/rest.php');
require_once('graphmapper.php');

class School extends Rest{
	
        private $collapsedName;
        private $optionsString;
        private $description;
        private $schoolId;
        private $name;
        private $tenantUUID;
        private $id;
        private $tenantDomain;

        private $graphmapper;
        
        /**
        * Contructs School Object
        *
        * @param string $tenantDomain Tenant Domain (externalId)
        * @param string $schoolId SchoolId (externalId)
        *
        */
        function __construct($tenantDomain = NULL, $schoolId=NULL) {
            
            if(!empty($tenantDomain) && !empty($schoolId)){
                
                $this->loadSchool($tenantDomain, $schoolId);
                $this->graphmapper = new GraphMapper($tenantDomain);
            }
            else{
                $this->graphmapper = new GraphMapper();
            }
            
        }
        
        public function getCollapsedName() {
            return $this->collapsedName;
        }

        public function getOptionsString() {
            return $this->optionsString;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getSchoolId() {
            return $this->schoolId;
        }

        public function getName() {
            return $this->name;
        }

        public function getTenantUUID() {
            return $this->tenantUUID;
        }

        public function getId() {
            return $this->id;
        }

        public function getTenantDomain() {
            return $this->tenantDomain;
        }

        
        public function setOptionsString($optionsString) {
            $this->optionsString = $optionsString;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function setSchoolId($schoolId) {
            $this->schoolId = $schoolId;
        }

        public function setName($name) {
            $this->name = $name;
        }

        
    
    
        /**
        * loadSchool
        * Gets a school from the graph using the given school Id
        *
        * @param $tenantDomain - External Id of the tenant the school belongs to
        * @param $schoolId - Internal Id of the school
        *
        * @return - set propeties of school object
        */
        function loadSchool($tenantDomain, $schoolId){

           //Build the URL of the REST endpoint
           $path = "tenant/" . $tenantDomain . "/school/" . $schoolId;

           //Make the REST call and decode the returned JSON string
           $school = parent::_getSISURL($path, null);

           $this->sisJSONToObject($school);

           $this->tenantDomain = $tenantDomain;
        }
	
        
         /**
        * loadSchexistsool
        * check if school exists using the given school Id
        *
        * @param $tenantDomain - External Id of the tenant the school belongs to
        * @param $schoolId - Internal Id of the school
        *
        * @return - true is School Exists   false- otherwise
        */
        public function exists($tenantDomain, $schoolId){
 
           //Build the URL of the REST endpoint
           $path = "tenant/" . $tenantDomain . "/school/" . $schoolId;

           try
           {
                //Make the REST call and decode the returned JSON string
                $school = parent::_getSISURL($path, null);

                if(isset($school->Id))
                {
                    return true;
                }
           }
           catch(Exception $e)
           {
               return false;
           }
        }
    
	/**
	 * add
	 * add a new school in the graph
	 *
         * @param $tenantExternalId - External Id of the tenant the school belongs to
         *
	 * @return - JSON object of School
	 */
	 function add($tenantDomain){
		
            //Build the URL of the REST endpoint
	    $path = "tenant/" . rawurlencode($tenantDomain) . "/school/create";

            //Make the REST call and decode the returned JSON string
            $school = array(
               
                'OptionsString' => $this->optionsString,
                'Description' => $this->getDescription(),
                'ExternalId' => $this->schoolId,
                'Name' => $this->name
                //,'TenantId' => $this->tenantUUID,
             );
        
              //  $school = array_filter($school, 'strlen');
        
		$response = parent::_postSISURL($path, null, json_encode($school));
		
                $this->tenantDomain = $tenantDomain;
                
                $this->createDefaultCourse();
                
                
		//Return the School portion of the decoded JSON object
		return $response;
	 }
        
        
        /**
	 * update
	 * update school in the graph
	 *
         *
	 * @return - updated object of School
	 */
	 function update(){
            
            if(empty($this->tenantDomain))
                throw new Exception ("tenantDomain is empty");
            
            if(empty($this->schoolId))
                throw new Exception ("schoolId is empty");
            
            
            //Build the URL of the REST endpoint
            $path = "tenant/" . rawurlencode($this->tenantDomain) . "/school/edit";

            //Make the REST call and decode the returned JSON string
              $school = array(
               
                'OptionsString' => $this->optionsString,
                'Description' => $this->description,
                'ExternalId' => $this->schoolId,
                'Name' => $this->name,
                'Id' => $this->id
             );
              
              
         //       $school = array_filter($school, 'strlen');
        
		$response = parent::_putSISURL($path, null, json_encode($school));
		
		//Return the School portion of the decoded JSON object
		return $response;
	}
    
    
        /**
         * Adds a user to a school in the graph
         *
         * @param $accountId - Account Id of the user to add
         * @param $userType - Defined role type of the user to add
         * 
         * @return string Returns confirmation messsage
	 */
         public function addUserToSchool($accountId, $userType){
                
            if(empty($accountId) || empty($userType))
            {
                throw new Exception("Account or userType parameter is empty.");
            }
            
            $userType = strtolower($userType);
            
            if(empty($this->tenantDomain) || empty($this->schoolId))
            {
                throw new Exception("TenantDomain or SchoolId property is empty. Please load school");
            }
        
            //Build the URL of the REST endpoint
            $path = "tenant/" . rawurlencode($this->tenantDomain) . "/" . rawurlencode($this->schoolId) . "/" . rawurlencode($userType) . "/addUser";
            
            $params = "userId=" . rawurlencode($accountId);
          
            //Make the REST call
             return parent::_getSISURL($path, $params);
         }
        
         
        
         /**
	 * Retrive attributes from SIS-REST User json object and set properties of this class
	 *
	 * @param $userJSON - JSON user object from SIS-REST
	 * 
         */
         private function sisJSONToObject($schoolJSON){
             
            
            $this->collapsedName = $schoolJSON->CollapsedName;
            $this->setOptionsString($schoolJSON->OptionsString);
            
            if(isset($schoolJSON->Description))
                $this->setDescription($schoolJSON->Description);
            
            $this->setSchoolId($schoolJSON->ExternalId);
            $this->setName($schoolJSON->Name);
            $this->tenantUUID =$schoolJSON->TenantId;
            $this->id = $schoolJSON->Id;

         }
     
         function createDefaultCourse(){
             
            if(empty($this->tenantDomain))
            {
               throw new Exception('Tenant domain is empty. Please load Tenant.');
                
            }
            
            $defaultSchoolId = $this->graphmapper->getDefaultSchool();
            $defaultCourseId = $this->graphmapper->getDefaultCourse();
            
            // Setup default Course
             try
             {
                $course = new Course($this->tenantDomain, $defaultSchoolId, $defaultCourseId);
                
             }  
             catch (Exception $e)
             {
                 // School Doesnt Exist
                 $course = new Course();
                 $course->setTitle($defaultCourseId);
                 $course->setCourseId($defaultCourseId);
                 $course->setDescription('default course for orphans accounts');
                 $course->add($this->tenantDomain, $defaultSchoolId);
                 
             }
             
         }
         
}

?>
