Feature: Country Lookup

Scenario: Lookup all countries
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create country lookup
    Then I lookup all countries

Scenario: Lookup country by code
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create country lookup
    Then I lookup country by code "us"

Scenario: Lookup country by invalid code
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create country lookup
    Then I lookup country by invalid code "abc"

Scenario: Lookup country by name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create country lookup
    Then I lookup country by name "USA"

Scenario: Lookup country by invalid name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create country lookup
    Then I lookup country by invalid name "ABC"

Scenario: Lookup provinces by country code
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create country lookup
    Then I lookup provinces by country code "us"

Scenario: Lookup provinces by invalid country code
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create country lookup
    Then I lookup provinces by invalid country code "ab"