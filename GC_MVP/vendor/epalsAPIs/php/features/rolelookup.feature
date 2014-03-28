Feature: Role Lookup

Scenario: Lookup all roles
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create role lookup
    Then I lookup all roles

Scenario: Lookup role by name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create role lookup
    Then I lookup role id "teacher" by name "Teacher"

Scenario: Lookup role by invalid name
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create role lookup
    Then I lookup role by invalid name "Abc"

Scenario: Lookup role by id
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create role lookup
    Then I lookup role "Student" by id "student"

Scenario: Lookup role by invalid id
    Given I create session by broker using username "steve@epals.com" and password "password"
    Then I create role lookup
    Then I lookup role by invalid id "abc"