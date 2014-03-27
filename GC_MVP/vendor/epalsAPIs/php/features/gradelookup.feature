Feature: Grade Lookup

Scenario: Lookup all grade
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create grade lookup
    Then I lookup all grades

Scenario: Lookup grade by id
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create grade lookup
    Then I lookup grade "Pre-K" by id "prek"

Scenario: Lookup grade by invalid id
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create grade lookup
    Then I lookup grade by invalid id "211"

Scenario: Lookup grade by name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create grade lookup
    Then I lookup grade id "kindergarten" by name "Kindergarten"

Scenario: Lookup grade by invalid name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create grade lookup
    Then I lookup grade by invalid name "nsxs"