Feature: Group

Scenario: Create group
   Given I create group "group1" in tenant "sfocil1.epals.com"
   And I add group

Scenario: Add teacher
   Given I create group "group2" in tenant "sfocil1.epals.com"
   And I add group
   And I create teacher "teacher1" for tenant "sfocil1.epals.com"
   When I add teacher to group
   Then teacher is in group

Scenario: Add student
   Given I create group "group3" in tenant "sfocil1.epals.com"
   And I add group
   And I create student "student1" for tenant "sfocil1.epals.com"
   When I add student to group 
   Then student is in group

Scenario:  Remove teacher
   Given I create group "group4" in tenant "sfocil1.epals.com"
   And I add group
   And I create teacher "teacher3" for tenant "sfocil1.epals.com"
   And I add teacher to group
   When I remove teacher from group
   Then teacher is not in group

Scenario:  Remove student
   Given I create group "group5" in tenant "sfocil1.epals.com"
   And I add group
   And I create student "student2" for tenant "sfocil1.epals.com"
   And I add student to group
   When I remove student from group
   Then student is not in group

Scenario: Delete group
   Given I create group "group6" in tenant "sfocil1.epals.com"
   And I add group
   When I delete group
   Then group is not in site