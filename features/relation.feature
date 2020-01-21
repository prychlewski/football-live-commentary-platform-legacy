Feature: Relation feature

  @database-clear
  Scenario: Add new event comment
    Given there is data in team table:
      | id  | name   |
      | 123 | Team-1 |
      | 213 | Team-2 |
    And there is data in event table:
      | id | host_team_id | guest_team_id | host_points | guest_points | date                |
      | 1  | 123          | 213           | 0           | 0            | 2020-12-11 20:30:12 |
    And I am authenticated as 'admin' with 'password' password
    And I send 'POST' request to '/api/relation/event/1' with data:
    """
    {
	  "content": "Example relation comment"
    }
    """
    Then the response status code should be 200
    And the response should contain "id"
    And the response should contain "date"
    And the JSON node "content" should be equal to "Example relation comment"

  Scenario: Edit event comment
    Given I am authenticated as 'admin' with 'password' password
    And I send 'PATCH' request to '/api/relation/1' with data:
    """
    {
	  "content": "Example relation comment - updated!"
    }
    """
    Then the response status code should be 200
    And the JSON node "content" should be equal to "Example relation comment - updated!"


  Scenario: Get all event comments
    Given there is data in event_comment table:
      | id | event_id | date                | content                |
      | 2  | 1        | 2020-12-11 21:30:12 | 'Another nice comment' |
    And I am authenticated as 'normal_user' with 'password' password
    And I send 'GET' request to '/api/relation/event/1/complete'
    Then the response status code should be 200
    And the response should contain array with 2 elements
