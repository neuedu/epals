<?php

$SISREST = "http://int-evn01.dev.epals.net:8080/sis/";


// Test on the SIS REST API
$TenantName = "TestTeant" . rand(0,1000);
$domain = $TenantName . ".epals.com";
$emaildomain = "email." .  $domain;
$tenantArray = array (
                'AppsEnabled' => null,
                'Description' => null,
                'Domain' => $domain,
                'ExternalId' => $domain,
                'EmailDomain' => $emaildomain,
                'EncryptionClass' => '',
                'Name' => $TenantName,
                'OptionsString' => '',
                'Published' => null                   
                );
$postdata = json_encode($tenantArray);
$SIS_URL = $SISREST . "tenant/create" . "?" . "format=json";;

$ch = curl_init();
curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));		
curl_setopt($ch, CURLOPT_URL, $SIS_URL);		
if (isset($postdata)){
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);   
}

$result = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_status == "200" or $http_status == "201") {
    print ("SIS API Server is configured properly for Tenant Provisioning.\n");   
}
else {
    print ("Cannot create tenant via SIS API Server.\n");
}


$UserName = 'zhua' . rand(0,1000) . "@" . $domain;
$Roles = array("Student");
$userArray = array (
                'ExternalId' => '2_810_1_1_10',
                'FirstName' => 'Zhili',
                'LastName' => 'Hua',
                'Rawdob' => '19800101',
                'ExternalEmail' => "zhua" . rand(0,1000) . "@corp.epals.com",
                'EPalsEmail' => "zhua" . rand(0, 1000) . "@epals.com",
                'Account' => $UserName,
                'Grade' => '11',
                'Roles' => $Roles,
                'Password' => 'zlhua211',
                'Disabled' => '',
                'OptionsString' => ''                    
                );

$postdata_user = json_encode($userArray);
$user_SIS_URL = $SISREST . "user/student/create" . "?" . "format=json";

$ch1 = curl_init();
curl_setopt ($ch1, CURLOPT_TIMEOUT, 20);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));		
curl_setopt($ch1, CURLOPT_URL, $user_SIS_URL);		
if (isset($postdata_user)){
    curl_setopt($ch1, CURLOPT_POST, true);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $postdata_user);   
}

$result1 = curl_exec($ch1);
$http_status1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
curl_close($ch1);

//print("$result1\n");
if ($http_status1 == "200" or $http_status1 == "201") {
    print ("SIS API Server is configured properly for User Provisioning.\n");   
}
else {
    print ("Cannot create User via SIS API Server.\n");
}
?>
