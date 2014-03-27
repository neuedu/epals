Feature: Community

Scenario: Create test community
    Given I create and add community "mycommunity"

Scenario: Create invalid community - empty name
    Given I create and add community ""

Scenario: Create community - empty description
    Given I create community
    When I set community name "emptydesc"
    And I set community description ""
    And I set community SSO realm "http://test.epals.com/sso/"
    Then I add community

Scenario: Create invalid community - empty SSO
    Given I create community
    When I set community name "emptysso"
    And I set community description "my test community"
    And I set community SSO realm ""
    Then I add community

Scenario: Load community by id
    Given I create and add community "last2communitybyid"
    When I load last community
    Then I check community name is "last2communitybyid"

Scenario: Load invalid community - empty id
    Given I create and add community "communitybyemptyid"
    When I load community by id ""

Scenario: Load community by name
    Given I create and add community "communitybyname"
    And I create community
    When I load community by name "communitybyname"
    Then I check community name is "communitybyname"

Scenario: Load invalid community - empty name
    Given I create and add community "communitybyemptyname"
    And I create community
    When I load community by name ""

Scenario: Update community - name
    Given I create and add community "updatename"
    When I set community name "myupdatedname"
    Then I update community

Scenario: Update community - description    
    Given I create and add community "updatedescription"
    When I set community description "myupdateddescription"
    Then I update community

Scenario: Update community - sso
    Given I create and add community "updatesso"
    When I set community SSO realm "http://testupdatesso.epals.com/sso/"
    Then I update community

Scenario: Update community - empty name
    Given I create and add community "updateemptyname"
    When I set community name ""
    Then I update community

Scenario: Update community - empty description
    Given I create and add community "updateemptydescription"
    When I set community description ""
    Then I update community

Scenario: Update community - empty sso
    Given I create and add community "updateemptysso"
    When I set community SSO realm ""
    Then I update community

Scenario: Update invalid community
    Given I create community
    When I update community

Scenario: Add tenant to community
    Given I create and add community "addvalidtenant"
    When I add tenant "sfocil1.epals.com" to community

Scenario: Add invalid tenant to community
    Given I create and add community "addinvalidtenant"
    When I add tenant "invalid" to community

Scenario: Add invalid tenant to community - empty tenant domain
    Given I create and add community "addemptytenant"
    When I add tenant "" to community