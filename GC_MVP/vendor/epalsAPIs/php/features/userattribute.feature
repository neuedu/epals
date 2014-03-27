Feature: User Attribute

Scenario: Create, add and update user attribute
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user attribute for user
   And I add attribute "Religion" with value "Sith"
   Then I update user attribute

Scenario: Create attribute for invalid user
   Given I create user attribute for "invalid"

Scenario: Add invalid user attribute
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user attribute for user
   Then I add attribute "" with value "test"
   
Scenario: Get invalid user attribute
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user attribute for user
   And I add attribute "Religion" with value "Sith"
   Then I get attribute "invalid"
   
Scenario: Get and Delete user attribute
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user attribute for user
   And I add attribute "Religion" with value "Sith"
   And I get attribute "Religion"
   Then I delete user attributes

Scenario: Get all attributes
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user attribute for user
   And I add attribute "Religion" with value "Sith"
   And I add attribute "Height" with value "5 foot 6 inches"
   Then I get all attributes