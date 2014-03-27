Feature: Course

Scenario: Create course
   Given I create course "CourseQA" for school "SchoolQATest" in tenant "sfocil1.epals.com"

Scenario: Create invalid course
   Given I create course "" for school "" in tenant "sfocil1.epals.com"