Feature: User Preference

Scenario: Create, add and update user preference
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user preference for user
   And I add user preference "Sport" with value "Soccer"
   Then I update user preferences

Scenario: Create user preference for invalid user - no domain
   Given I create user preference for "invalid"

Scenario: Create user preference for invalid user - with domain 
   Given I create user preference for "invalid@sfocil1.epals.com"

Scenario: Create user preference for invalid user - empty
   Given I create user preference for ""

Scenario: Get and Delete user preference
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user preference for user
   And I add user preference "Sport" with value "Soccer"
   And I get preference "Sport"
   Then I delete preferences

Scenario: Add valid preference - empty value
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user preference for user
   Then I add user preference "test" with value ""

Scenario: Add invalid preference - empty key
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user preference for user
   Then I add user preference "" with value "test"

Scenario: Add invalid preference - empty value and key
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user preference for user
   Then I add user preference "" with value ""

Scenario: Get invalid preference
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user preference for user
   And I add user preference "Genre" with value "Action"
   Then I get preference "Movie"

Scenario: Get all preferences
   Given I create user "sfocil" for tenant "sfocil1.epals.com"
   And I add user
   And I load user "sfocil" from tenant "sfocil1.epals.com"
   When I create user preference for user
   And I add user preference "Sport" with value "Soccer"
   And I add user preference "Religion" with value "Catholic"
   Then I get all preferences