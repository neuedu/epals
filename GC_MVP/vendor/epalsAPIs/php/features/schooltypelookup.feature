Feature: SchoolType Lookup

Scenario: Lookup all schooltype
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create schooltype lookup
    Then I lookup all schooltypes

Scenario: Lookup schooltype by name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create schooltype lookup
    Then I lookup schooltype id "home" by name "Home"

Scenario: Lookup schooltype by invalid name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create schooltype lookup
    Then I lookup schooltype by invalid name "Abc"

Scenario: Lookup schooltype by id
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create schooltype lookup
    Then I lookup schooltype name "Home" by id "home"

Scenario: Lookup schooltype by invalid id
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create schooltype lookup
    Then I lookup schooltype by invalid id "abc"