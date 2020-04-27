@ccei @core @api
Feature: CCEI Content Types
  Makes sure that the ccei subject content type was created during installation.

  @page @ccei-subject
  Scenario: Make sure that the content types provided for CCEI Subject are present.
    Given I am logged in as a user with the administrator role
    When I visit "/node/add"
    Then I should see "CCEI Subject"

  @page @ccei-subject
  Scenario: Creating a CCEI Subject
    Given I am logged in as a user with the administrator role
    When I visit "node/add/ccei_subject"
    And I enter "Electricity" for "title[0][value]"
    And I enter "Electricity comprises of the generation of electric power transmission from generating facilities to distrubution centres and/or distrubution to end users, as well as the electricity consumption by the end users. Electricity can be generated from hydropower, fossil fuels, nuclear power or other processes." for "body[0][value]"
    And I enter "https://dv-vd.cloud.statcan.ca/home/index/CCEI-Overview_en" for "field_subject_powerbi[0][url]"
    And I enter "100%" for "field_subject_powerbi[0][width]"
    And I enter "100%" for "field_subject_powerbi[0][width]"
    And I enter "/electricity" for "path[0][alias]"
    Given I check the box "edit-status-value"
    Given I press "Save"
    Then I wait 3 seconds
    Then I visit "/electricity"
    Then I should see "Electricity comprises of the generation of electric power transmission from generating facilities to distrubution centres and/or distrubution to end users, as well as the electricity consumption by the end users. Electricity can be generated from hydropower, fossil fuels, nuclear power or other processes."
