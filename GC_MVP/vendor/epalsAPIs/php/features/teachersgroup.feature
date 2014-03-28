Feature: Teachers Group

Scenario: Create group
   Given I create group "teachersgroup"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"

Scenario: Create invalid group
   Given I create group "teachersgroup1"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"

Scenario: Add teacher to group
   Given I create group "group"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"
   And I create teacher "teacher" for tenant "sfocil1.epals.com"
   When I add teacher to group
   Then teacher is in group

Scenario: Add invalid teacher to group
   Given I create group "group"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"
   When I create teacher "teacher" for tenant "sfocil2.epals.com"
   Then I add teacher to group

Scenario: Add student to group
   Given I create group "group"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"
   And I create student "student" for tenant "sfocil1.epals.com"
   When I add student to group 
   Then student is in group

Scenario: Add invalid student to group
   Given I create group "group"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"
   When I create student "student" for tenant "sfocil2.epals.com"
   Then I add student to group 
   
Scenario:  Remove teacher from group
   Given I create group "group"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"
   And I create teacher "teacher" for tenant "sfocil1.epals.com"
   And I add teacher to group
   When I remove teacher from group
   Then teacher is not in group

Scenario:  Remove invalid teacher from group
   Given I create group "group"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"
   When I create teacher "teacher" for tenant "sfocil1.epals.com"
   Then I remove teacher from group

Scenario:  Remove student from group
   Given I create group "group"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"
   And I create student "student" for tenant "sfocil1.epals.com"
   And I add student to group
   When I remove student from group
   Then student is not in group

Scenario:  Remove invalid student from group
   Given I create group "group"
   And I add group for school "SchoolQATest" and course "CourseQA" in tenant "sfocil1.epals.com"
   When I create student "student" for tenant "sfocil1.epals.com"
   Then I remove student from group
   