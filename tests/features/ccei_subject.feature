@ccei @core @api
Feature: CCEI Content Types
  Makes sure that the ccei subject content type was created during installation.

  @page @ccei-subject
  Scenario: Make sure that the content types provided for CCEI Subject are present.
    Given I am logged in as a user with the administrator role
    When I visit "/node/add"
    Then I should see "CCEI Subject"

  @page @ccei-subject @javascript
  Scenario: Creating a CCEI Subject
    Given I am logged in as a user with the administrator role
    When I visit "node/add/ccei_subject"
    Then I enter "Electricity" for "title[0][value]"
    And I select "full_html" from "body[0][format]"
    And I enter "Electricity comprises of the generation of electric power transmission from generating facilities to distrubution centres and/or distrubution to end users, as well as the electricity consumption by the end users. Electricity can be generated from hydropower, fossil fuels, nuclear power or other processes." for "body[0][value]"
    And I enter "https://dv-vd.cloud.statcan.ca/home/index/CCEI-Overview_en" for "field_subject_powerbi[0][url]"
    And I enter "100%" for "field_subject_powerbi[0][width]"
    And I enter "100%" for "field_subject_powerbi[0][height]"
    And I check the box "edit-status-value"
    Then I click the "#edit-submit" element
    Then I should see "Electricity comprises of the generation of electric power transmission from generating facilities to distrubution centres and/or distrubution to end users, as well as the electricity consumption by the end users. Electricity can be generated from hydropower, fossil fuels, nuclear power or other processes."
