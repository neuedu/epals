<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once('config.php');
require_once('classes/rest.php');
require_once('tenant.php');
require_once('graphmapper.php');


/**
 * 
 * Community class has feature and functionalities to create/update communities
 *
 * @author nehalsyed
 */
class Community extends Rest{
   
    
        private $id; //uuid
        private $name;
        private $description;
        private $ssorealm;
    
        
        
        /**
	* Constructor for Creating community object
        * 
        * Object can be created using either $communityuuid or $name 
        * 
        * @param string $communityuuid Community UUID 
        * @param string $name Community Name
        *   
        * @return void
        */
        function __construct($communityuuid = NULL, $name = NULL) {
           
            $this->graphMapper = new GraphMapper();
            
            if(isset($communityuuid))
            {
                $this->loadCommunityById($communityuuid);
            }
            else if(isset($name))
            {
                $this->loadCommunityByName($name);
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

        public function getSsorealm() {
            return $this->ssorealm;
        }


        public function setName($name) {
            $this->name = $name;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function setSsorealm($ssorealm) {
            $this->ssorealm = $ssorealm;
        }

        
        /**
	 * load class properties using community UUID
         * 
         * @param string $communityuuid Community uuid
         * 
         * @return void
	 */
        function loadCommunityById($communityuuid)
        {
            if(!isset($communityuuid))
                throw Exception("communityuuid parameter is empty");
            
             $path = "/community/uuid/".rawurlencode($communityuuid);
             $community = parent::_getSISURL($path, NULL);
             
             
             $this->sisJSONToObject($community);
        }
        
        /**
	 * load class properties using community name
         * 
         * @param string $name Community name
         * 
         * @return void
	 */
        function loadCommunityByName($name)
        {
            if(!isset($name) || trim($name) === '')
                throw new Exception("Community name parameter is empty");
            
             $path = "/community/".rawurlencode($name);
             $community = parent::_getSISURL($path, NULL);      
             $this->sisJSONToObject($community);
        }
        
        
         /**
	 * Add a community to database
         * 
         * @return - object of comunity created otherwise Exception
	 */
         function add() {
           
            if(!isset($this->name) || trim($this->name)==='')
                  throw new Exception("Community name parameter is empty");
           
            
            $path = "community/create";
            
            $community = array (
                'Name' => $this->name,
                'Description' => $this->description,
                'SSORealm' => $this->ssorealm,
                );
            
            $response = parent::_postSISURL($path, null, json_encode($community));
            
            if(isset($response->Id)){
                
                $this->id = $response->Id;
                return $response->Id;
            }
             
            return $response;
            
         }
        
        
        /**
        * Update properties of a Community account
        *
        * @return object Update community Object 
        */
        function update(){

            if( !isset($this->id))
                throw new Exception ("Id not set. Please load community via constructor");
            
            if(!isset($this->name) || trim($this->name)==='')
                  throw new Exception("Community name parameter is empty");
            
            //Build the URL of the REST endpoint
            $getpath = "community/uuid/".  rawurlencode($this->id);  //Current bug in SIS requires extra .com

            $community = parent::_getSISURL($getpath, NULL);

            $community = $this->updateCommunity($community);

            $updatepath = "community/edit";

            $result = parent::_putSISURL($updatepath, null, json_encode($community));

            return $result;
        }
        
        
        
        /**
	 * Add a tenant to community
         * 
         * @return - object of comunity created otherwise Exception
	 */
         function addTenant($tenantDomain) {
           
            if(!isset($this->id))
                  throw  new Exception("Id not found. Please load Community via constructor!");
            
            if(!isset($tenantDomain))
                  throw  new Exception("Tenant Domain is empty.");
           
            $tenant = new Tenant($tenantDomain);
            
            $path = "community/".rawurlencode($this->id)."/addTenant";
            
            $params = "tenantId=".rawurlencode($tenant->getDomain());
            
            $response = parent::_getSISURL($path, $params);
            
            return $response;
            
        }
      
        
         /**
	 * update community object with currect object properties
         * 
         * @return void
	 */
         private function updateCommunity($community)
         {
             
           if(!is_null($this->id))
                $community->Id = $this->id;
            
            if(!is_null($this->name))
                $community->Name = $this->name;
            
            if(!is_null($this->description))
                $community->Description = $this->description;
            
            if(!is_null($this->ssorealm)){
                $community->SSORealm = $this->ssorealm;
            
                if(isset($community->ssorealm))
                    $community->ssorealm = $this->ssorealm;
            }
            
            
            if(isset($community->NodeId))
                unset($community->NodeId);
            
            if(isset($community->NodeName))
                unset($community->NodeName);
             
             return $community;
         }
         
         
        
        /**
	 * Retrive attributes from SIS-REST Community json object and set properties of this class
	 *
	 * @param string $communityJSON - JSON community object from SIS-REST
	 * 
         */
         private function sisJSONToObject($communityJSON){
             
            $this->id = $communityJSON->Id;
            
            if(isset($communityJSON->Name))
                $this->name = $communityJSON->Name;
            
            if(isset($communityJSON->SSORealm))
                $this->ssorealm = $communityJSON->SSORealm;
            
            if(!isset($communityJSON->SSORealm) && isset($communityJSON->ssorealm))
                $this->ssorealm = $communityJSON->ssorealm;
            
            if(isset($communityJSON->Description))
                $this->description = $communityJSON->Description;
           
          }
        
}

?>
