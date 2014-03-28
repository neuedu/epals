Feature: Tenant

Scenario: Create and add test tenant
   Given I create and add test tenant

Scenario: Create and add tenant
   Given I create new tenant "mytest"
   When I add tenant  
   And I load tenant "mytest"
   Then domain in loaded tenant is "mytest"

Scenario: Load invalid tenant
   Given I create new tenant "mytest"
   When I add tenant  
   Then I load tenant "invalid"

Scenario: Update tenant
   Given I create new tenant "mytest"
   When I add tenant  
   And I load tenant "mytest"
   Then I set tenant name "Tenantmytest"
   And I update tenant
   And I check tenant name "Tenantmytest"

Scenario: Update invalid tenant - empty name
   Given I create new tenant "mytest"
   When I add tenant  
   And I load tenant "mytest"
   Then I set tenant name ""
   And I update tenant

Scenario: Update invalid tenant - empty domain
   Given I create new tenant "mytest"
   When I add tenant  
   And I load tenant "mytest"
   Then I set tenant domain ""
   And I update tenant