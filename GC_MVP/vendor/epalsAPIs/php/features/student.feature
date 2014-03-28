Feature: Student

Scenario: Add Student
    Given I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"

Scenario: Add student to invalid school
    Given I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "invalid"

Scenario: Update student
    Given I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    When I set student data
    | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password | rawDob | grade |
    | teacher | sfocil1.epals.com | corp.epals.com | gmail.com | 2_810_1_1_ | test | mytest | epals123 | 19800101 | 12 |
    Then I update student

Scenario: Update student with invalid data
    Given I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    When I set student data
    | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password | rawDob | grade |
    |  | sfocil1.epals.com | corp.epals.com | gmail.com | 2_810_1_1_ | test | mytest | epals123 | 19800101 | 12 |
    Then I update student

Scenario: Add moderator to student
    Given I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    When I create teacher "moderator" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    Then I add moderator "moderator" to student

Scenario: Add invalid moderator to student
    Given I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    When I create teacher "moderator" for tenant "sfocil1.epals.com"
    Then I add moderator "invalid" to student    

Scenario: Remove moderator from student
    Given I create student "qastudent" for tenant "sfocil1.epals.com"
    And I add student to school "SchoolQATest"
    And I create teacher "moderator" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    When I add moderator "moderator" to student
    Then I remove moderator "moderator" from student

Scenario: Remove invalid moderator from student
    Given I create student "qastudent" for tenant "sfocil1.epals.com"
    When I add student to school "SchoolQATest"
    And I create teacher "moderator" for tenant "sfocil1.epals.com"
    And I add teacher to school "SchoolQATest"
    Then I remove moderator "moderator" from student
    
