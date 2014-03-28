Feature: Age Range Lookup

Scenario: Lookup all agerange
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create agerange lookup
    Then I lookup all ageranges

Scenario: Lookup agerange by id
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create agerange lookup
    Then I lookup agerange "8-10" by id "2"

Scenario: Lookup agerange by invalid id
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create agerange lookup
    Then I lookup agerange by invalid id "211"

Scenario: Lookup agerange by name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create agerange lookup
    Then I lookup agerange id "2" by name "8-10"

Scenario: Lookup agerange by invalid name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create agerange lookup
    Then I lookup agerange by invalid name "<7"