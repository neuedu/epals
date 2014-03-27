Feature: Teacher

Scenario: Add Teacher
    Given I create teacher "qateacher" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"

Scenario: Add invalid teacher
    Given I create teacher "qateacher" for tenant "sfocil1.epals.com"
    When I set teacher data
    | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password |
    |  | sfocil1.epals.com | corp.epals.com | gmail.com | 2_810_1_1_ | test | mytest | epals123 |
    Then I add teacher to school "SchoolQATest"

Scenario: Update teacher
    Given I create teacher "qateacher" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    When I set teacher data
    | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password |
    | teacher | sfocil1.epals.com | corp.epals.com | gmail.com | 2_810_1_1_ | test | mytest | epals123 |
    Then I update teacher

Scenario: Update teacher with invalid data
    Given I create teacher "qateacher" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    When I set teacher data
    | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password |
    |  | sfocil1.epals.com | corp.epals.com | gmail.com | 2_810_1_1_ | test | mytest | epals123 |
    Then I update teacher

Scenario: Add student to teacher
    Given I create teacher "qateacher" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    When I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    Then I add student to teacher

Scenario: Add invalid student to teacher
    Given I create teacher "qateacher" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    When I create student "qastudent" for tenant "sfocil1.epals.com"
    Then I add student to teacher

Scenario: Remove student from teacher
    Given I create teacher "qateacher" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    When I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    And I add student to teacher
    Then I remove student from teacher

Scenario: Remove invalid student from teacher
    Given I create teacher "qateacher" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    When I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    Then I remove student from teacher