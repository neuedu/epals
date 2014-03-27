Feature: Event

Scenario: Create and add event
   Given I create event 
   When I set event type "log"
   And I include data "user" "sfocil1"
   And I set event data "mydata"
   And I set callback "mailEventsCallback"
   Then I add event

Scenario: Add an invalid event
   Given I create event 
   Then I add event

Scenario: I create empty event
   Given I create event 
   When I set event type ""
   And I include data "" ""
   And I set event data ""
   And I set callback ""
   And I add event

Scenario: Add same event twice
   Given I create event 
   When I set event type "log"
   And I include data "user" "sfocil1"
   And I set event data "mydata"
   And I set callback "mailEventsCallback"
   Then I add event
   And I add event

Scenario: Add event with special chars
   Given I create event 
   When I set event type "test"
   And I include data "user" "nsyed"
   And I set event data "#$@@!@$#$#Q_)()*&*\''&*"
   And I set callback "mailEventsCallback"
   Then I add event

Scenario: Get event
   Given I create event 
   When I set event type "test"
   And I include data "user" "nsyed"
   And I set event data "#$@@!@$#$#Q_)()*&*\''&*"
   And I set callback "mailEventsCallback"
   Then I add event
   And I get event

Scenario: Get invalid event
   Given I create event 
   When I set event type "test"
   And I include data "user" "nsyed"
   And I set event data "#$@@!@$#$#Q_)()*&*\''&*"
   And I set callback "mailEventsCallback"
   Then I get event

Scenario: Get event by key
   Given I create event 
   When I set event type "test"
   And I include data "user" "getbykey"
   And I set event data "mydata"
   And I set callback "myEventsCallback"
   Then I add event
   And I get event by key "user" "getbykey"

Scenario: Get invalid event by key
   Given I create event 
   When I set event type "test"
   And I include data "user" "getbykey"
   And I set event data "mydata"
   And I set callback "myEventsCallback"
   Then I add event
   And I get event by key "invalid" "invalid"

Scenario: Delete event
   Given I create event 
   When I set event type "test"
   And I include data "user" "getbykey"
   And I set event data "mydata"
   And I set callback "myEventsCallback"
   Then I add event
   And I delete event

Scenario: Delete invalid event
   Given I create event 
   When I set event type "test"
   And I include data "user" "getbykey"
   And I set event data "mydata"
   And I set callback "myEventsCallback"
   Then I delete event

