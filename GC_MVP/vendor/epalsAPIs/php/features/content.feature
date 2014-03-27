Feature: Content

Scenario: Create and add content
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Sergio Focil"
    And I set data to "my data"
    And I set url to "http://themovieaddicts.blogspot.com"
    And I set title to "MyTitle"
    And I add content
    Then I lookup content by key "author" "Sergio Focil" with data "my data"

Scenario: Add an empty content
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Nehal Syed"
    And I set data to "This is test content"
    And I set url to "http://themovieaddicts.blogspot.com"
    And I set title to "This is Test"
    Then I add content
    And I lookup content by key "author" "Nehal Syed" with data "This is test content"

Scenario: Add same content twice
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Nehal Syed"
    And I set data to "This is test content"
    And I set url to "http://themovieaddicts.blogspot.com"
    And I set title to "This is Test"
    Then I add content
    And I lookup content by key "author" "Nehal Syed" with data "This is test content"
    And I add content

Scenario: Add empty content
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to ""
    And I set data to ""
    And I set url to ""
    And I set title to ""
    Then I get exception on add content with message "Must have content to save."

Scenario: Add with no author
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set data to "my data"
    And I set url to ""
    And I set title to "title 1"
    Then I get exception on add content with message "Author cannot be empty."

Scenario: Add with no title
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Nehal Syed"
    And I set data to "my data"
    Then I get exception on add content with message "Title cannot be empty."


Scenario: Delete content
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Sergio Focil"
    And I set data to "my data"
    And I set url to "http://themovieaddicts.blogspot.com"
    And I set title to "MyTitle"
    And I add content
    Then I lookup content by key "author" "Sergio Focil" with data "my data"
    And I delete content
    
Scenario: Delete invalid content
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Sergio Focil"
    And I set data to "my data"
    And I set url to "http://themovieaddicts.blogspot.com"
    And I set title to "MyTitle"
    Then I add content
    Then I delete content

Scenario: test chinese characters
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Sergio Focil"
    And I set data to "国际战略研究所称"
    And I set url to "http://themovieaddicts.blogspot.com"
    And I set title to "国际战 略研究 所称"
   Then I add content

Scenario: load Content test
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Sergio Focil"
    And I set data to "my data"
    And I set url to "http://themovieaddicts.blogspot.com"
    And I set title to "MyTitle"
    And I add content
    Then I load last created content


Scenario: Test Update method
    Given I create content
    When I set tenant to "sfocil1.epals.com"
    And I set author to "Sergio Focil"
    And I set data to "my data update test"
    And I set url to "http://themovieaddicts.blogspot.com"
    And I set title to "Update Test"
    And I add content
    And I load last created content
    And I set data to "This is Changed"
    Then I update content
    Then I lookup content by key "author" "Sergio Focil1" with data "This is Changed"

Scenario: add content with invalid tenant
    Given I create content
    When I set tenant to "sfocil2.epals.com" and get exception with message "Tenant External Id 'sfocil2.epals.com' was not found."
   