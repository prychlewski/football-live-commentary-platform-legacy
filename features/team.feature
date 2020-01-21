Feature: Team feature

  @database-clear
  Scenario: Add new team
    Given I am authenticated as 'admin' with 'password' password
    And I send 'POST' request to '/api/team' with data:
    """
    {
	  "name": "Example team name"
    }
    """
    Then the response status code should be 200
    And the JSON node 'id' should exist

  @database-clear
  Scenario: Edit team
    Given there is data in team table:
      | id  | name              |
      | 123 | Example team name |
    And I am authenticated as 'admin' with 'password' password
    And I send 'PATCH' request to '/api/team/123' with data:
    """
    {
	  "name": "Example team name - edited"
    }
    """
    Then the response status code should be 200
    And the JSON node 'name' should contain 'Example team name - edited'

  Scenario: View team
    Given I am authenticated as 'admin' with 'password' password
    And I send 'GET' request to '/api/team/123'
    Then the response status code should be 200
    And the JSON node 'id' should exist
    And the JSON node 'name' should exist

  Scenario: Delete team
    Given I am authenticated as 'admin' with 'password' password
    And I send 'DELETE' request to '/api/team/123'
    Then the response status code should be 204
