Feature: Users

Scenario: Create new user, add to existing tenant, load user, and verify password
  Given I create user "sfocil" for tenant "sfocil1.epals.com"
  When I add user
  And I load user "sfocil" from tenant "sfocil1.epals.com"
  Then user "sfocil" is in tenant "sfocil1.epals.com"
  And user password is "Password123"
  But user password is not "epals123"

Scenario: Update and verify password with valid value
  Given I create user "sfocil" for tenant "sfocil1.epals.com"
  And I add user
  And I load user "sfocil" from tenant "sfocil1.epals.com"
  When I update password "epals123"
  Then user password is "epals123"

Scenario: Update password and verify invalid value
  Given I create user "sfocil" for tenant "sfocil1.epals.com"
  And I add user
  And I load user "sfocil" from tenant "sfocil1.epals.com"
  When I update password "epals123"
  And I load user "sfocil" from tenant "sfocil1.epals.com"
  Then user password is ""

Scenario: Update password to inexistent user
  Given I create user "inexistent" for tenant "sfocil1.epals.com"
  When I update password "epals123"

Scenario: Update password with empty value
  Given I create user "sfocil" for tenant "sfocil1.epals.com"
  Given I update password ""

Scenario: Create new user and add twice to existing tenant
  Given I create user "sfocil" for tenant "sfocil1.epals.com"
  When I add user
  Then I cant add user again

Scenario: Verify user does not exist
  Given I create user "inexistent" for tenant "sfocil1.epals.com"
  Then user "inexistent" is not in tenant "sfocil1.epals.com"

Scenario: Fail to verify user existence
  Given user "" is not in tenant "sfocil1.epals.com"

Scenario: Load inexistent user
  Given I create new user
  Then I load user "inexistent" from tenant "sfocil1.epals.com"

Scenario: Load invalid user value
  Given I create new user
  Then I load user "" from tenant "sfocil1.epals.com"

Scenario: Add user with no data
  Given I create new user
  When I add user

Scenario Outline: Create and add users with different data
  Given I create new user
  When I set user info: "<username>" "<domain>" "<ePalsEmail>" "<externalEmail>" "<userId>" "<firstName>" "<lastName>" "<password>" "<rawDob>" "<grade>" "<role>"
  Then I add user

  Examples: 
  | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password | rawDob | grade | role |
  |  | sfocil1.epals.com | test@corp.epals.com | test2@gmail.com | 2_810_1_1_20 | test | mytest | epals123 | 19800101 | 12 | student |
  | test3 |  | test@corp.epals.com | test3@gmail.com | 2_810_1_1_30 | test | mytest | epals123 | 19800101 | 12 | student |
  | test4 | sfocil1.epals.com |  | test4@gmail.com | 2_810_1_1_40 | test | mytest | epals123 | 19800101 | 12 | student |
  | test5 | sfocil1.epals.com | test@corp.epals.com |  | 2_810_1_1_50 | test | mytest | epals123 | 19800101 | 12 | student |
  | test6 | sfocil1.epals.com | test@corp.epals.com | test6@gmail.com |  | test | mytest | epals123 | 19800101 | 12 | student |
  | test7 | sfocil1.epals.com | test@corp.epals.com | test7@gmail.com | 2_810_1_1_70 |  | mytest | epals123 | 19800101 | 12 | student |
  | test8 | sfocil1.epals.com | test@corp.epals.com | test8@gmail.com | 2_810_1_1_80 | test |  | epals123 | 19800101 | 12 | student |
  | test9 | sfocil1.epals.com | test@corp.epals.com | test9@gmail.com | 2_810_1_1_90 | test | mytest |  | 19800101 | 12 | student |
  | test10 | sfocil1.epals.com | test@corp.epals.com | test10@gmail.com | 2_810_1_1_100 | test | mytest | epals123 |  | 12 | student |
  | test11 | sfocil1.epals.com | test@corp.epals.com | test11@gmail.com | 2_810_1_1_100 | test | mytest | epals123 | 19800101 |  | student |
  | test12 | sfocil1.epals.com | test@corp.epals.com | test12@gmail.com | 2_810_1_1_100 | test | mytest | epals123 | 19800101 | 12 |  |

Scenario: Update user data
  Given I create user "sfocil" for tenant "sfocil1.epals.com"
  When I set user data:
  | username | domain | ePalsEmail | externalEmail | userId | firstName | lastName | password | rawDob | grade | role |
  | test412 | sfocil1.epals.com | test412@corp.epals.com | test412@gmail.com | 2_810_1_1_412 | test | mytest | epals123 | 19800101 | 12 | student |
  Then I update user

Scenario: Delete user
  Given I create user "sfocil" for tenant "sfocil1.epals.com"
  When I add user
  Then I delete user

Scenario: Delete invalid user
  Given I create user "sfocil" for tenant "sfocil1.epals.com"
  When I delete user
  