Feature: Parent

Scenario: Add Student
    Given I create parent "qaparent" for tenant "sfocil1.epals.com"
    And I add parent to school "SchoolQATest"

Scenario: Add invalid student
    Given I create parent "qaparent" for tenant "sfocil1.epals.com"
    When I set parent data
    | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password | rawDob | grade |
    |  | sfocil1.epals.com | corp.epals.com | gmail.com | 2_810_1_1_ | test | mytest | epals123 | 19800101 | 12 |
    Then I add parent to school "SchoolQATest"

Scenario: Update student
    Given I create parent "qaparent" for tenant "sfocil1.epals.com"
    And I add parent to school "SchoolQATest"
    When I set parent data
    | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password | rawDob | grade |
    | teacher | sfocil1.epals.com | corp.epals.com | gmail.com | 2_810_1_1_ | test | mytest | epals123 | 19800101 | 12 |
    Then I update parent

Scenario: Update student with invalid data
    Given I create parent "qaparent" for tenant "sfocil1.epals.com"
    And I add parent to school "SchoolQATest"
    When I set parent data
    | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password | rawDob | grade |
    |  | sfocil1.epals.com | corp.epals.com | gmail.com | 2_810_1_1_ | test | mytest | epals123 | 19800101 | 12 |
    Then I update parent

Scenario: Add student to parent
    Given I create parent "qaparent" for tenant "sfocil1.epals.com"
    And I add parent to school "SchoolQATest"
    And I create student "qastudent" for tenant "sfocil1.epals.com"
    When I add student to school "SchoolQATest"
    Then I add student to parent
    
Scenario: Add invalid student to parent
    Given I create parent "qaparent" for tenant "sfocil1.epals.com"
    And I add parent to school "SchoolQATest"
    When I create student "qastudent" for tenant "sfocil1.epals.com"
    Then I add student to parent

Scenario: Remove student from parent
    Given I create parent "qaparent" for tenant "sfocil1.epals.com"
    And I add parent to school "SchoolQATest"
    And I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    When I add student to parent
    Then I remove student from parent
    
Scenario: Remove invalid student from parent
    Given I create parent "qaparent" for tenant "sfocil1.epals.com"
    And I add parent to school "SchoolQATest"
    And I create student "qastudent" for tenant "sfocil1.epals.com"
    When I add student to school "SchoolQATest"
    Then I remove student from parent