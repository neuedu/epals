Feature: School

Scenario: Add test School
    Given I create and add test school

Scenario: Add, check exists, and load
    Given I create school "myschool"
    And I add school in tenant "sfocil1.epals.com"
    When I check school "myschool" in tenant "sfocil1.epals.com"
    Then I load school "myschool" from tenant "sfocil1.epals.com"

Scenario: Check inexistent school
    Given I create school "myschool"
    When I add school in tenant "sfocil1.epals.com"
    Then I check school "invalid" in tenant "sfocil1.epals.com"

Scenario: Add invalid school
    Given I create school "myschool"
    When I set school name ""
    Then I add school in tenant "sfocil1.epals.com"

Scenario: Load invalid school
    Given I create school "myschool"
    When I add school in tenant "sfocil1.epals.com"
    Then I load school "invalid" from tenant "sfocil1.epals.com"

Scenario: Add user to school
    Given I create school "myschool"
    And I add school in tenant "sfocil1.epals.com"
    And I create user "test" for tenant "sfocil1.epals.com"
    And I add user
    When I check school "myschool" in tenant "sfocil1.epals.com"
    Then I load school "myschool" from tenant "sfocil1.epals.com"
    And I add user to school

Scenario: Add invalid user to school
    Given I create school "myschool"
    And I add school in tenant "sfocil1.epals.com"
    And I create user "test" for tenant "sfocil1.epals.com"
    When I check school "myschool" in tenant "sfocil1.epals.com"
    Then I load school "myschool" from tenant "sfocil1.epals.com"
    And I add user to school
    