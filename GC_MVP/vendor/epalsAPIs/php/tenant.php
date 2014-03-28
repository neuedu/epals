<?
/**
 * This is part of a Demo PHP application for demonstrating the ePals Webservice APIs
 * 
 * class Tenant: contain method belong to tenant
 * 
 * @copyright ePals Inc, all rights reserved
 */

require_once('classes/rest.php');
require_once('config.php');
require_once('graphmapper.php');
require_once('school.php');
require_once('course.php');
require_once('section.php');

class Tenant extends Rest
{

        private $appsEnabled; // services enabled for this tenanat ex: CloudAgnostic
        private $collapsedName; // used to generate broadcasting email address
        private $description; // description of tenanrt
        private $domain; // required: unique domain for tenant
        private $emailDomain; // required
        private $encryptionClass; // password encryption class
        private $name; // required: tenant anme
        private $serviceMetadata=''; // services metadata
        private $published= false; // tehant is published if atleast one school is provisioned.
        private $id;

        private $graphmapper;
     
        /**
        * Constructor of Tenant Class
        *
        * @param string $tenantDomain Tenant domain in URL format
        *
        */
        function __construct($tenantDomain = NULL) {

               if(!empty($tenantDomain)){

                   $this->loadTenant($tenantDomain);
                   $this->graphmapper = new GraphMapper($tenantDomain);
               }
                else {
                    $this->graphmapper = new GraphMapper(NULL);
                }
        }
        
        
     
        public function getAppsEnabled() {
            return $this->appsEnabled;
        }

        public function getCollapsedName() {
            return $this->collapsedName;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getDomain() {
            return $this->domain;
        }

        public function getEmailDomain() {
            return $this->emailDomain;
        }

        public function getEncryptionClass() {
            return $this->encryptionClass;
        }

        public function getName() {
            return $this->name;
        }

        public function getServiceMetadata() {
            return $this->serviceMetadata;
        }

        public function getPublished() {
            return $this->published;
        }


        public function setAppsEnabled($appsEnabled) {
            $this->appsEnabled = $appsEnabled;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function setDomain($domain) {
            $this->domain = $domain;
        }

        public function setEmailDomain($emailDomain) {
            $this->emailDomain = $emailDomain;
        }

        public function setEncryptionClass($encryptionClass) {
            $this->encryptionClass = $encryptionClass;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setServiceMetadata($serviceMetadata) {
            $this->serviceMetadata = $serviceMetadata;
        }

        public function setPublished($published) {
            $this->published = $published;
        }

        public function getId() {
            return $this->id;
        }

          
          
        /**
        * Load Tenant properties from graph
        *
        * @param $domain - tenant domain (formally externalId)
        *
        */
        function loadTenant($domain)
        {

           $path = "/tenant/".rawurlencode($domain);
           $tenant = parent::_getSISURL($path, NULL);
           $this->sisJSONToObject($tenant);

        }
     
     
     
         /**
	 * Add a tenant to the graph
	 * 
         * @return - tenant object that was created
	 */
        function add() {
           
            
            if(empty($this->domain) || empty($this->emailDomain) || empty($this->name))
            {
                throw new Exception("Either of these properties are empty : Domain, emailDomain, name");
            }
               
            $path = "tenant/create";
           
            $tenantArray = array (
                'AppsEnabled' => $this->appsEnabled,
                'Description' => $this->description,
                'Domain' => $this->domain,
                'ExternalId' => $this->domain,
                'EmailDomain' => $this->emailDomain,
                'EncryptionClass' => $this->encryptionClass,
                'Name' => $this->name,
                'OptionsString' => $this->serviceMetadata,
                'Published' => $this->published
                   
                );
           
            //$tenantArray = array_filter($tenantArray, 'strlen');
            
            $response = parent::_postSISURL($path, null, json_encode($tenantArray));
           
            $this->createDefaultSchool();
            
            return $response;
        }
        
         /**
	 * update tenant in graph
         * Important: load Tenant Object before calling update method to avoid overriding properties
	 * 
         * @return - tenant object that was updated
	 */
        function update() {
            
            if(empty($this->id)){
               
                throw new Exception("Id value is empty. Please call loadTenant before update");
            }
            
            if(empty($this->domain) || 
               trim($this->domain) === '' ||
               empty($this->name) ||
               trim($this->name) === '') {
                throw new Exception("Tenant Domain/Name cannot be set to null or empty string");
            }
            
            $path = "tenant/edit";
            
            $tenantArray = array (
                'Id' => $this->id,
                'AppsEnabled' => $this->appsEnabled,
                'Description' => $this->description,
                'Domain' => $this->domain,
                'ExternalId' => $this->domain,
                'EmailDomain' => $this->emailDomain,
                'EncryptionClass' => $this->encryptionClass,
                'Name' => $this->name,
                'OptionsString' => $this->serviceMetadata,
                'Published' => $this->published
                );
            
             //$tenantArray = array_filter($tenantArray, 'strlen');
             
            $response = parent::_putSISURL($path, null, json_encode($tenantArray));
            
            return $response;
        }
        
        
         /**
	 * Retrive attributes from SIS-REST User object and set properties of this class
	 *
	 * @param $userJSON -  user object from SIS-REST
	 * 
         */
        private function sisJSONToObject($userJSON){
            
            if(isset($userJSON->AppsEnabled))
                $this->setAppsEnabled($userJSON->AppsEnabled);
            if(isset($userJSON->CollapsedName))
                $this->collapsedName = $userJSON->CollapsedName;
            if(isset($userJSON->Description))
                $this->setDescription($userJSON->Description);
            if(isset($userJSON->Domain))
                $this->setDomain($userJSON->Domain);
            if(isset($userJSON->EmailDomain))
                $this->setEmailDomain($userJSON->EmailDomain);
            if(isset($userJSON->EncryptionClass))
                $this->setEncryptionClass($userJSON->EncryptionClass);
            if(isset($userJSON->Id))
                $this->id = $userJSON->Id;
            if(isset($userJSON->Name))
                $this->setName($userJSON->Name);
            if(isset($userJSON->OptionsString))
                $this->setServiceMetadata($userJSON->OptionsString);
            if(isset($userJSON->Published))
                $this->setPublished($userJSON->Published);
            if(isset($userJSON->Id))
                $this->id = $userJSON->Id;
           
        }
     
        
        private function createDefaultSchool()
         {
             if(!isset($this->domain))
                 throw new Exception("Domain is not set");
             
             $defaultSchoolId = $this->graphmapper->getDefaultSchool();
             
             try
             {
                $school = new School($this->domain, $defaultSchoolId);
                
             }  
             catch (Exception $e)
             {
                 // School Doesnt Exist
                 $school = new School();
                 $school->setName($defaultSchoolId);
                 $school->setSchoolId($defaultSchoolId);
                 $school->setDescription('default school for orphans accounts');
                 $school->add($this->domain);
                 
             }
             
             return $school;
         }
         
         
        /**
	 * Create default School, course and section in tenant if not exist
	 *
	 */
        function setupDefaults()
        {
            if(empty($this->domain))
            {
               throw new Exception('Tenant domain is empty. Please load Tenant.');
                
            }
            
            
            $defaultSchoolId = $this->graphmapper->getDefaultSchool();
            $defaultCourseId = $this->graphmapper->getDefaultCourse();
            $defaultSectionId = $this->graphmapper->getDefaultSection();
            
            // Setup default School
             try
             {
                $school = new School($this->domain, $defaultSchoolId);
                
             }  
             catch (Exception $e)
             {
                 // School Doesnt Exist
                 $school = new School();
                 $school->setName($defaultSchoolId);
                 $school->setSchoolId($defaultSchoolId);
                 $school->setDescription('desfault school for orphans accounts');
                 $school->add($this->domain);
                 
             }
             
             // Setup default Course
             try
             {
                $course = new Course($this->domain, $defaultSchoolId, $defaultCourseId);
                
             }  catch (Exception $e)
             {
                 // School Doesnt Exist
                 $course = new Course();
                 $course->setTitle($defaultCourseId);
                 $course->setCourseId($defaultCourseId);
                 $course->setDescription('default course for orphans accounts');
                 $course->add($this->domain, $defaultSchoolId);
                 
             }
            
            // Setup default Section
             try
             {
                $section = new Section($this->domain, $defaultSchoolId, $defaultCourseId, $defaultSectionId);
                
             }  catch (Exception $e)
             {
                 // School Doesnt Exist
                 $section = new Section();
                 $section->setSectionId($defaultSectionId);
                 $section->add($this->domain, $defaultSchoolId, $defaultCourseId);
                 
             }
        }
        
}           


?>
