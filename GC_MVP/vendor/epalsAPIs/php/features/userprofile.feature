Feature: User Profile

Scenario: Set profile
   Given I create a user profile
   Given I create session using username "steve@epals.com" and password "password"
   Given I set session to profile
   When I set schoolname "School1" in profile 
   When I set account "nsyed@epals.com" in profile 
   When I add profile


Scenario: load profile without session
   Given I create a user profile
   When I set schoolname "School2" in profile 
   When I set account "nsyed@epals.com" in profile 
   When I fail adding profile with message "session_id is required in the request object"

Scenario: Set profile
   Given I create a user profile
   Given I create session using username "steve@epals.com" and password "password"
   Given I set session to profile
   When I set schoolname "School3" in profile 
   When I set teachername "Teacher1" in profile 
   When I set account "nsyed@epals.com" in profile
   When I set email "nsyed@yahoo.com" in profile
   When I set description "Desc" in profile  
   When I set street1 "Street1" in profile  
   When I set street2 "Street2" in profile  
   When I set city "City1" in profile  
   When I set state "State1" in profile  
   When I set country "Country1" in profile  
   When I set phone "316-322-4423" in profile 
   When I set skypename "nehalepals" in profile 
   When I set skypevisibility "visible" in profile 
   When I set agerange "18-21" in profile 
   When I set numstudents "18" in profile 
   When I set languages "EN,DH" in profile 
   When I set subjects "Subject1" in profile 
   When I set collaboration "Collaboration1" in profile 
   When I set visibility "Visible" in profile 
 
   When I add profile
   Given I load last created user profile and verify schoolname in profile
   Given I load last created user profile and verify teachername in profile
   Given I load last created user profile and verify description in profile
   Given I load last created user profile and verify street1 in profile
   Given I load last created user profile and verify street2 in profile
   Given I load last created user profile and verify city in profile
   Given I load last created user profile and verify state in profile
   Given I load last created user profile and verify country in profile
   Given I load last created user profile and verify phone in profile
   Given I load last created user profile and verify skypename in profile
   Given I load last created user profile and verify skypevisibility in profile
   Given I load last created user profile and verify agerange in profile
   Given I load last created user profile and verify numstudents in profile
   Given I load last created user profile and verify languages in profile
   Given I load last created user profile and verify subjects in profile
   Given I load last created user profile and verify collaboration in profile
   Given I load last created user profile and verify visibility in profile
   Given I load last created user profile and verify email in profile


   Scenario: Delete profile
   Given I create a user profile
   Given I create session using username "steve@epals.com" and password "password"
   Given I set session to profile
   When I set schoolname "School4" in profile 
   When I set account "nsyed@epals.com" in profile 
   When I add profile
   When I delete profile
   Then I fail loading profile with message "Could not find a profile for the specified id"
   
   Scenario: Approve profile
   Given I create a user profile
   Given I create session using username "steve@epals.com" and password "password"
   Given I set session to profile
   When I set schoolname "School5" in profile 
   When I set account "nsyed@epals.com" in profile 
   When I add profile
   Then I approve profile using admin username "singasonga@epals.com" and password "asma72"
   Then I sleep for 1 seconds
   Given I load last created user profile and verify schoolname in profile
   Then Profile status is approved

   Scenario: Delete profile
   Given I create a user profile
   Given I create session using username "steve@epals.com" and password "password"
   Given I set session to profile
   When I set schoolname "School6" in profile 
   When I set account "nsyed@epals.com" in profile 
   When I add profile
   Given I load last created user profile and verify schoolname in profile
   Then I delete profile


Scenario: Hold profile
   Given I create a user profile
   Given I create session using username "steve@epals.com" and password "password"
   Given I set session to profile
   When I set schoolname "School7" in profile 
   When I set account "nsyed@epals.com" in profile 
   When I add profile
   Then I hold profile using admin username "singasonga@epals.com" and password "asma72" with comments "Holding incomplete info"
   Given Profile status is hold
 


Scenario: Update profile
   Given I create a user profile
   Given I create session using username "steve@epals.com" and password "password"
   Given I set session to profile
   When I set schoolname "School3" in profile 
   When I set teachername "Teacher1" in profile 
   When I set account "nsyed@epals.com" in profile
   When I set email "nsyed@yahoo.com" in profile
   When I set description "Desc" in profile  
   When I set street1 "Street1" in profile  
   When I set street2 "Street2" in profile  
   When I set city "City1" in profile  
   When I set state "State1" in profile  
   When I set country "Country1" in profile  
   When I set phone "316-322-4423" in profile 
   When I set skypename "nehalepals" in profile 
   When I set skypevisibility "visible" in profile 
   When I set agerange "18-21" in profile 
   When I set numstudents "18" in profile 
   When I set languages "EN,DH" in profile 
   When I set subjects "Subject1" in profile 
   When I set collaboration "Collaboration1" in profile 
   When I set visibility "Visible" in profile 
 
   When I add profile
   Given I load last created user profile and verify id in profile
   Given I load last created user profile and verify schoolname in profile
   Given I load last created user profile and verify teachername in profile
   Given I load last created user profile and verify description in profile
   Given I load last created user profile and verify street1 in profile
   Given I load last created user profile and verify street2 in profile
   Given I load last created user profile and verify city in profile
   Given I load last created user profile and verify state in profile
   Given I load last created user profile and verify country in profile
   Given I load last created user profile and verify phone in profile
   Given I load last created user profile and verify skypename in profile
   Given I load last created user profile and verify skypevisibility in profile
   Given I load last created user profile and verify agerange in profile
   Given I load last created user profile and verify numstudents in profile
   Given I load last created user profile and verify languages in profile
   Given I load last created user profile and verify subjects in profile
   Given I load last created user profile and verify collaboration in profile
   Given I load last created user profile and verify visibility in profile
   Given I load last created user profile and verify email in profile
  
   When I set schoolname "aSchool3" in profile 
   When I set teachername "aTeacher1" in profile 
   When I set account "ansyed@epals.com" in profile
   When I set email "ansyed@yahoo.coma" in profile
   When I set description "aDesc" in profile  
   When I set street1 "aStreet1" in profile  
   When I set street2 "aStreet2" in profile  
   When I set city "aCity1" in profile  
   When I set state "aState1" in profile  
   When I set country "aCountry1" in profile  
   When I set phone "116-322-4423" in profile 
   When I set skypename "anehalepals" in profile 
   When I set skypevisibility "avisible" in profile 
   When I set agerange "19-21" in profile 
   When I set numstudents "19" in profile 
   When I set languages "EN,AH" in profile 
   When I set subjects "aSubject1" in profile 
   When I set collaboration "aCollaboration1" in profile 
   When I set visibility "aVisible" in profile 
   Then I update profile

   Given I load last created user profile and verify schoolname in profile
   Given I load last created user profile and verify teachername in profile
   Given I load last created user profile and verify description in profile
   Given I load last created user profile and verify street1 in profile
   Given I load last created user profile and verify street2 in profile
   Given I load last created user profile and verify city in profile
   Given I load last created user profile and verify state in profile
   Given I load last created user profile and verify country in profile
   Given I load last created user profile and verify phone in profile
   Given I load last created user profile and verify skypename in profile
   Given I load last created user profile and verify skypevisibility in profile
   Given I load last created user profile and verify agerange in profile
   Given I load last created user profile and verify numstudents in profile
   Given I load last created user profile and verify languages in profile
   Given I load last created user profile and verify subjects in profile
   Given I load last created user profile and verify collaboration in profile
   Given I load last created user profile and verify visibility in profile
   Given I load last created user profile and verify email in profile


Scenario: Creating without school name
   Given I create a user profile
   Given I create session using username "steve@epals.com" and password "password"
   When I set schoolname "School11" in profile 
   Given I set session to profile
   When I add profile and get Exception
