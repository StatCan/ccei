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
    When I scroll down and click the "#edit-field-subject-indicators-actions-ief-add" element
    Then I wait 1 seconds
    And I enter "Installed capacity" for "field_subject_indicators[form][inline_entity_form][field_indicator_title][0][value]"
    And I enter "Megawatts" for "field_subject_indicators[form][inline_entity_form][field_units][0][value]"
    And I select "round" from "field_subject_indicators[form][inline_entity_form][field_value_format]"
    And I enter "25100022" for "field_subject_indicators[form][inline_entity_form][field_indicator_sources][0][subform][field_pid][0][value]"
    And I fill in "field_subject_indicators[form][inline_entity_form][field_indicator_sources][0][subform][field_coordinates][0][value]" with:
      """
        1.1.1
        1.1.2
        1.1.3
        1.1.4
        1.1.5
        1.1.6
        1.1.7
        1.1.8
        1.1.9
        1.1.10
        2.1.1
        2.1.2
        2.1.3
        2.1.4
        2.1.5
        2.1.6
        2.1.7
        2.1.8
        2.1.9
        2.1.10
        2.2.1
        2.2.2
        2.2.3
        2.2.4
        2.2.5
        2.2.6
        2.2.7
        2.2.8
        2.2.9
        2.2.10
        4.3.1
        4.3.2
        4.3.3
        4.3.4
        4.3.5
        4.3.6
        4.3.7
        4.3.8
        4.3.9
        4.3.10
        4.4.1
        4.4.2
        4.4.3
        4.4.4
        4.4.5
        4.4.6
        4.4.7
        4.4.8
        4.4.9
        4.4.10
        5.1.1
        5.1.2
        5.1.3
        5.1.4
        5.1.5
        5.1.6
        5.1.7
        5.1.8
        5.1.9
        5.1.10
        6.4.1
        6.4.2
        6.4.3
        6.4.4
        6.4.5
        6.4.6
        6.4.7
        6.4.8
        6.4.9
        6.4.10
        7.4.1
        7.4.2
        7.4.3
        7.4.4
        7.4.5
        7.4.6
        7.4.7
        7.4.8
        7.4.9
        7.4.10
        8.2.1
        8.2.2
        8.2.3
        8.2.4
        8.2.5
        8.2.6
        8.2.7
        8.2.8
        8.2.9
        8.2.10
        9.1.1
        9.1.2
        9.1.3
        9.1.4
        9.1.5
        9.1.6
        9.1.7
        9.1.8
        9.1.9
        9.1.10
        9.2.1
        9.2.2
        9.2.3
        9.2.4
        9.2.5
        9.2.6
        9.2.7
        9.2.8
        9.2.9
        9.2.10
        10.1.1
        10.1.2
        10.1.3
        10.1.4
        10.1.5
        10.1.6
        10.1.7
        10.1.8
        10.1.9
        10.1.10
        10.2.1
        10.2.2
        10.2.3
        10.2.4
        10.2.5
        10.2.6
        10.2.7
        10.2.8
        10.2.9
        10.2.10
        11.1.1
        11.1.2
        11.1.3
        11.1.4
        11.1.5
        11.1.6
        11.1.7
        11.1.8
        11.1.9
        11.1.10
        11.2.1
        11.2.2
        11.2.3
        11.2.4
        11.2.5
        11.2.6
        11.2.7
        11.2.8
        11.2.9
        11.2.10
        12.4.1
        12.4.2
        12.4.3
        12.4.4
        12.4.5
        12.4.6
        12.4.7
        12.4.8
        12.4.9
        12.4.10
        13.1.1
        13.1.2
        13.1.3
        13.1.4
        13.1.5
        13.1.6
        13.1.7
        13.1.8
        13.1.9
        13.1.10
        14.1.1
        14.1.2
        14.1.3
        14.1.4
        14.1.5
        14.1.6
        14.1.7
        14.1.8
        14.1.9
        14.1.10
      """
    When I scroll down and click the '[id^="edit-field-subject-indicators-form-inline-entity-form-actions-ief-add-save"]' element
    Then I wait 1 seconds
    And I enter "https://dv-vd.cloud.statcan.ca/home/index/CCEI-Overview_en" for "field_powerbi[0][uri]"
    And I enter "https://www150.statcan.gc.ca/t1/tbl1/en/tv.action?pid=2510002201" for "field_powerbi_data[0][uri]"
    And I enter "Table 25-10-0022-01 Installed plants, annual generating capacity by type of electricity generation" for "field_powerbi_data[0][title]"
    And I check the box "edit-status-value"
    Then I click the "#edit-submit" element
    Then I wait 1 seconds
    Then I should see "Electricity comprises of the generation of electric power transmission from generating facilities to distrubution centres and/or distrubution to end users, as well as the electricity consumption by the end users. Electricity can be generated from hydropower, fossil fuels, nuclear power or other processes."
