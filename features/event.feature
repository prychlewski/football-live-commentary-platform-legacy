Feature: Event feature

  @database-clear
  Scenario: Add new event
    Given there is data in team table:
      | id  | name   |
      | 123 | Team-1 |
      | 213 | Team-2 |
      | 321 | Team-3 |
    And I am authenticated as 'admin' with 'password' password
    And I send 'POST' request to '/api/event' with data:
    """
    {
      "hostTeamId": 123,
      "guestTeamId": 213,
      "date": "2019-12-11 20:30:12"
    }
    """
    Then the response status code should be 200
    And the JSON nodes should contain:
      |id|1|
      |hostTeamId|123|
      |guestTeamId|213|
      |hostPoints|0|
      |guestPoints|0|
    And the response should contain "date"

  Scenario: Edit event
    Given I am authenticated as 'admin' with 'password' password
    And I send 'PATCH' request to '/api/event/1' with data:
    """
    {
      "hostTeamId": 321,
      "guestTeamId": 213,
      "date": "2020-12-11 20:30:12"
    }
    """
    Then the response status code should be 200
    And the JSON nodes should contain:
      |hostTeamId|321|
      |guestTeamId|213|

  Scenario: View event
    Given I am authenticated as 'admin' with 'password' password
    And I send 'GET' request to '/api/event/1'
    Then the response status code should be 200
    And the JSON nodes should contain:
      |id|1|
      |hostTeamId|321|
      |guestTeamId|213|
      |hostPoints|0|
      |guestPoints|0|
    And the response should contain "date"

  Scenario: Score a goal during event
    Given I am authenticated as 'admin' with 'password' password
    And I send 'PATCH' request to 'api/event/1/goal' with data:
    """
    {
      "teamId": 321
    }
    """
    And the response status code should be 204
    Then I send 'GET' request to '/api/event/1'
    And the JSON node "hostPoints" should be equal to "1"

  Scenario: Delete event
    Given I am authenticated as 'admin' with 'password' password
    And I send 'DELETE' request to '/api/event/1'
    Then the response status code should be 204
