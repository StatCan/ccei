@ccei @core @api
Feature: CCEI Content Types
  Makes sure that the ccei Highlight content type was created during installation.

  @page @landing-page
  Scenario: Make sure that the content types provided for CCEI Highlight are present.
    Given I am logged in as a user with the administrator role
    When I visit "/node/add"
    Then I should see "CCEI Highlight"

  @page @javascript
  Scenario: Ensure that the WYSIWYG editor is present.
    Given I am logged in as a user with the administrator role
    When I visit "node/add/ccei_highlight"
    Then CKEditor "edit-body-0-value" should exist

  Scenario: The basic block content type should have a body field.
    Given I am logged in as a user with the "administrator" role
    When I visit "/block/add"
    Then I should see a "Body" element
