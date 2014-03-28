<?

/**
 * This is part of a Demo PHP application for demonstrating the ePals Webservice APIs
 * 
 * 
 * @copyright ePals Inc, all rights reserved
 */
require_once('config.php');
require_once('classes/rest.php');

class Section extends Rest {

    private $id;  //uuid
    private $sectionId; // ExternalId
    private $tenantDomain;
    private $schoolId;
    private $courseId;
    private $notes;
    private $meetingTimes;
    private $startDate;
    private $endDate;
    

     /**
     * Construct Section Object
     *
     * @param string $tenantDomain Tenant domain URL
     * @param string $schoolId SchoolID (externalID)
     * @param string $courseId  CourseID (externalId)
     * @param string $sectionId SectionID(externalId)
     *
     */
     function __construct($tenantDomain = NULL, $schoolId = NULL, $courseId = NULL, $sectionId = NULL) {

        if (!empty($tenantDomain) && !empty($schoolId) && !empty($courseId) && !empty($sectionId)) {

            $this->loadSection($tenantDomain, $schoolId, $courseId, $sectionId);
        }
        
        $this->tenantDomain = $tenantDomain;
        $this->sectionId = $sectionId;
     }

     public function getSectionId() {
         return $this->sectionId;
     }

     public function getTenantDomain() {
         return $this->tenantDomain;
     }

     public function getSchoolId() {
         return $this->schoolId;
     }

     public function getCourseId() {
        return $this->courseId;
     }

     public function getNotes() {
        return $this->notes;
     }

     public function getMeetingTimes() {
        return $this->meetingTimes;
     }

     public function getStartDate() {
        return $this->startDate;
     }
     
     public function getId() {
        return $this->id;
     }

     public function getEndDate() {
        return $this->endDate;
     }

     public function setSectionId($sectionId) {
        $this->sectionId = $sectionId;
     }

     public function setNotes($notes) {
        $this->notes = $notes;
     }

     public function setMeetingTimes($meetingTimes) {
        $this->meetingTimes = $meetingTimes;
     }

     public function setStartDate($startDate) {
        $this->startDate = $startDate;
     }

     public function setEndDate($endDate) {
        $this->endDate = $endDate;
     }

    /**
     * Loads section from the graph using the given section Id
     *
     * @param string $tenantDomain Tenant domain URL
     * @param string $schoolId SchoolID (externalID)
     * @param string $courseId  CourseID (externalId)
     * @param string $sectionId SectionID(externalId)
     *
     */
     function loadSection($tenantDomain, $schoolId, $courseId, $sectionId) {


        if (empty($tenantDomain) || empty($schoolId) || empty($courseId) || empty($sectionId)) {

            throw new Exception("Either of these parameters are empty : tenantDomain, schoolId, courseId, sectionId (name)");
        }

        //Build the URL of the REST endpoint
        $path = "tenant/" . rawurlencode($tenantDomain) . "/school/" . rawurlencode($schoolId) . "/course/" . rawurlencode($courseId) . "/section/" . rawurlencode($sectionId);

        //Make the REST call and decode the returned JSON string
        $section = parent::_getSISURL($path, null);

        if (!isset($section))
            throw new Exception("Record not found in graph!");

        $this->sisJSONToObject($section);

        //Return the Section portion of the decoded JSON object
        $this->tenantDomain = $tenantDomain;
        $this->schoolId = $schoolId;
        $this->courseId = $courseId;
     }

     /**
     * Updates a section in the graph
     *
     * @return string section object from REST-Api
     */
     function update() {


        if (empty($this->tenantDomain) || empty($this->schoolId) || empty($this->courseId) || empty($this->sectionId)) {

            throw new Exception("Either of these parameters are empty : tenantDomain, schoolId, courseId, sectionId(name)");
        }

        $section = array(
            'ExternalId' => $this->sectionId,
            'Notes' => $this->notes,
            'MeetingTimes' => $this->meetingTimes,
            'StartDate' => $this->startDate,
            'EndDate' => $this->endDate
        );

        $section = array_filter($section, 'strlen');

        //Build the URL of the REST endpoint
        $path = "tenant/" . rawurlencode($this->tenantDomain) . "/school/" . rawurlencode($this->schoolId) . "/course/" .
                rawurlencode($this->courseId) . "/section/edit";

        //Make the REST call and decode the returned JSON string
        $request = json_encode($section);
        
        return parent::_putSISURL($path, null, $request);
     }

     /**
     * add a section in the graph
     *
     * @param string $tenantDomain domain URL of tenant
     * @param string $schoolId SchoolID (externalID)
     * @param string $courseId CourseID (externalId)
     *
     * @return string Section object
     */
     function add($tenantDomain, $schoolId, $courseId) {

        if (empty($tenantDomain) || empty($schoolId) || empty($courseId) || empty($this->sectionId)) {

            throw new Exception("Either of these parameters or properties are empty : tenantDomain, schoolId, courseId, sectionId(name)");
        }


        //Build the URL of the REST endpoint
        $path = "tenant/" . rawurlencode($tenantDomain) . "/school/" . rawurlencode($schoolId) . "/course/" .
                rawurlencode($courseId) . "/section/create";

        //Make the REST call and decode the returned JSON string
        $section = array(
            'ExternalId' => $this->sectionId,
            'Notes' => $this->notes,
            'MeetingTimes' => $this->meetingTimes,
            'StartDate' => $this->startDate,
            'EndDate' => $this->endDate
        );

        //$section = array_filter($section, 'strlen');

        $response = parent::_postSISURL($path, null, json_encode($section));

        $this->tenantDomain = $tenantDomain;
        $this->schoolId = $schoolId;
        $this->courseId = $courseId;

        //Return the Course portion of the decoded JSON object
        return $response;
     }

    /*
     *  Add User to Section
     * 
     * @param string $userAccountId User account id (email format) to add to section
     * @param string $userType ex: Student, Teacher)
     * 
     * @return $userType: returns confirmation message
     */
     function addSectionEnrollment($userAccountId, $userType) {

        if (empty($userAccountId) || empty($userType) || empty($this->tenantDomain) || empty($this->schoolId) || empty($this->courseId) || empty($this->sectionId)) {

            throw new Exception("Either of these parameters or properties are empty : userAccountId, userType, tenantDomain, schoolId, courseId, sectionId");
        }

        // REST API accepts student or teacher as usertype
        if (strtolower($userType) == 'educator')
            $userType = 'teacher';

        $_tenantDomain = rawurlencode($this->tenantDomain);
        $_schoolId = rawurlencode($this->schoolId);
        $_courseId = rawurlencode($this->courseId);
        $_sectionId = rawurlencode($this->sectionId);
        $userType = rawurlencode($userType);
        $userAccountId = rawurlencode($userAccountId);

        $params = "userId=$userAccountId";

        // endpoint of service
        $url = "/tenant/$_tenantDomain/$_schoolId/$_courseId/$_sectionId/$userType/addUser";

        return parent::_getSISURL($url, $params);
     }

    /*
     * Remove User from Section
     * 
     * @param string $userAccountId User account id (email format)
     * @param string $userType User role (ex: Student, Teacher)
     * 
     * @return returns confirmation message
     */
     function deleteSectionEnrollment($userAccountId, $userType) {

        $tenantId = rawurlencode($this->tenantDomain);

        $enrollment = array(
            'SchoolExternalId' => $this->schoolId,
            'CourseExternalId' => $this->courseId,
            'SectionExternalId' => $this->sectionId,
            'UserAccountId' => $userAccountId,
            'MembershipType' => strtoupper($userType)
        );

        $post = array($enrollment);

        // delete enrollment end point
        $url = "tenant/section/unenroll/$tenantId";

        // encode json before passing to method
        return parent::_deleteSISURLSimple($url, json_encode($post));
     }
     
     
     
     
       /**
        * get Members of section
        * 
        * 
        * @return array array contains Users of section
        */
       function getMembers(){
           
           
           if(!isset($this->id))
                 throw new Exception("UUID is not set. Please load details via constructor");
             
             $path = "accessmanager/getUsers";
             
             $param = "sectionid=".rawurlencode($this->id);
             
             $members = parent::_getPMURL($path, $param);
             
             return $members->getUsers[0]->Users;
           
       }
       
       

     
     /*
     * Check user enrollment in Section as Student or Teacher
     * 
     * @param string $userAccountId User account id (email format)
     * 
     * @return returns confirmation message
     */
     function isEnrolled($userAccountId) {

        
         $mems = $this->getMembers();
         
         foreach ($mems as $mem) {
         
             if(strtolower($mem->accountId) == strtolower($userAccountId))
                 return true;
         }
         return false;
     }
     
     
     
     
     /**
     * Retrive section attributes from SIS-REST Course object and set properties of this class
     *
     * @param string $sectionJSON - JSON section object from SIS-REST
     * 
     */
     private function sisJSONToObject($sectionJSON) {

        $this->setSectionId($sectionJSON->ExternalId);
        
        $this->id  = $sectionJSON->Id;
       
        if(isset($sectionJSON->Notes))
            $this->setNotes($sectionJSON->Notes);
        
        if(isset($sectionJSON->EndDate))
            $this->setEndDate($sectionJSON->EndDate);
        
        if(isset($sectionJSON->StartDate))
            $this->setStartDate($sectionJSON->StartDate);
        
        if(isset($sectionJSON->MeetingTimes))
            $this->setMeetingTimes($sectionJSON->MeetingTimes);
     }

}

?>
