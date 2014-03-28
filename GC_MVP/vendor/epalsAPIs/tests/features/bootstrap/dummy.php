<?

/*
 * Copyright ePals, Inc.
 * 
 * User class that interacts with the SIS & Policy Manager rest apis. 
 * 
 */

//require_once('config.php');
//require_once('rest.php');

class dummy  {
	
        private $account; //required: user account id in format [username]@[tenant_Domain]
        private $ePalsEmail; // required: user email in format [username]@[tenant_EmailDomain]
        private $externalEmail; // user external email address ex: nsyed@mac.com
	private $userId; // user external id
	private $firstName; //required: user first or given name
        private $grade; // required: grade in case of student
	private $lastName; //required: user last or sur name
	private $rawDob; // student date of birth in format yyyymmdd ex: 19960101
	private $role; //req - (will retired) use role in system. possible values are 1. Student 2. Teacher 3. DistrictAdmin 4. Parent
        private $tenantDomain; // tenant domain 
        private $internalId; // internal UUID
        private $disabled = false; 
        private $userMetaData =''; // exteral field to hold extended data
        private $encryptedPasword; // user encrypted password
        private $password;
        
    
}

?>