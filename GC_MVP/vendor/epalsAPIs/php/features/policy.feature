Feature: Policy

Scenario: Create and save policy
    Given I create policy "mypolicy"

Scenario: Create same policy multiple times
    Given I create policy "once"
    When I create policy "once"
    Then I create policy "once"

Scenario: Create invalid policy
    Given I create policy ""
    When I create role ""
    And I allow word ""
    And I save policy

Scenario: Create policy, create role and allow word
    Given I create policy "policy1"
    And I create role "allow1" 
    When I allow word "pork"
    And I save policy
    Then word "pork" is allowed in role "allow1"

Scenario: Create policy, create role and deny word
    Given I create policy "policy2"
    And I create role "deny1" 
    When I deny word "pork"
    And I save policy
    Then word "pork" is denied in role "deny1"

Scenario: Create invalid role
    Given I create role "" 

Scenario: Create invalid role
    Given I create policy "newpolicy"
    Given I create role "" 
    When I deny word "invalid"
    Then I save policy

Scenario: Create policy, create role and deny invalid word
    Given I create policy "policy3"
    When I create role "deny2" 
    Then I deny word ""
    And I save policy

Scenario: Create policy, create role and allow invalid word
    Given I create policy "policy4"
    When I create role "allow2" 
    Then I allow word ""
    And I save policy

Scenario: Check inexistent word
    Given I create policy "policy5"
    And I create role "deny3" 
    When I deny word "pork"
    And I save policy
    Then word "invalid" is denied in role "deny3"

Scenario: Check word in inexistent role
    Given I create policy "policy6"
    And I create role "deny4" 
    When I allow word "pork"
    And I save policy
    Then word "pork" is allowed in role "invalid"

Scenario: Check invalid word
    Given I create policy "policy7"
    And I create role "deny5" 
    When I deny word "pork"
    And I save policy
    Then word "" is allowed in role "deny5"

Scenario: Check invalid word
    Given I create policy "policy8"
    And I create role "deny6" 
    When I deny word "pork"
    And I save policy
    Then word "" is denied in role "deny6"

Scenario: Delete policy
    Given I create policy "deleteme"
    When I delete policy