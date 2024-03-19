@block @block_news
Feature: Show news
  In order to be informed about news
  As a user
  I need to be able to see that the news block shows the intended news.

  Background:
    Given the following "blocks" exist:
      | blockname | contextlevel | reference  | pagetypepattern | defaultregion |
      | news      | System       | 1          | site-index      | side-pre      |
    And the following config values are set as admin:
      | config                  | value                  | plugin     |
      | title1                  | News Title One         | block_news |
      | description1            | News Description One   | block_news |
      | link1                   | https://apple.com      | block_news |
      | title2                  | News Title Two         | block_news |
      | description2            | News Description Two   | block_news |
      | link2                   | https://google.com     | block_news |
      | title3                  | News Title Three       | block_news |
      | description3            | News Description Three | block_news |
      | link3                   | https://ucl.ac.uk      | block_news |

  Scenario: See news as intended.
    When I am on site homepage
    Then I should see "News"
    And I should see "News Title One"
    And I should see "News Title Two"
    And I should see "News Title Three"
