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
    And I enter "https://dv-vd.cloud.statcan.ca/home/index/CCEI-Overview_en" for "field_powerbi[0][uri]"
    And I enter "https://www150.statcan.gc.ca/t1/tbl1/en/tv.action?pid=2510002201" for "field_powerbi_data[0][uri]"
    And I enter "Table 25-10-0022-01 Installed plants, annual generating capacity by type of electricity generation" for "field_powerbi_data[0][title]"
    And I check the box "edit-status-value"
    Then I click the "#edit-submit" element
    Then I should see "Electricity comprises of the generation of electric power transmission from generating facilities to distrubution centres and/or distrubution to end users, as well as the electricity consumption by the end users. Electricity can be generated from hydropower, fossil fuels, nuclear power or other processes."
