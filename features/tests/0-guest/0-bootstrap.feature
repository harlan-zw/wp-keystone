@guest
Feature: Bootstrap Application
  Initialize our browser making sure that the site can be loaded and
  the window is the right size.

  @javascript
  Scenario: Application can be bootstrap
    When I resize the window to desktop
    And I am on the homepage
    Then I should see "Latest Listings Added"
    And I should see "Login"
    And I should see "Signup"

