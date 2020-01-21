Feature: User feature

  @database-clear
  Scenario: Register new user
    Given I send 'POST' request to '/api/user/register' with data:
    """
    {
      "username": "test@test.pl",
      "password": "password123"
    }
    """
    Then the response status code should be 200
    And the JSON node 'token' should exist

  @database-clear
  Scenario: Register new admin user
    Given I am authenticated as 'admin' with 'password' password
    And I send 'POST' request to '/api/user/admin' with data:
    """
    {
      "username": "admin@test.pl",
      "password": "password123"
    }
    """
    Then the response status code should be 200
    And the JSON node 'roles[0]' should contain 'ROLE_ADMIN'

  Scenario: Non admin users cannot register new admin users
    Given I am authenticated as 'normal_user' with 'password' password
    And I send 'POST' request to '/api/user/admin' with data:
    """
    {
      "username": "admin@test.pl",
      "password": "password123"
    }
    """
    Then the response status code should be 403

  Scenario: I can check my user info
    Given I am authenticated as 'admin' with 'password' password
    And I send 'GET' request to '/api/me'
    Then the response status code should be 200
    And the JSON node 'username' should contain 'admin'
