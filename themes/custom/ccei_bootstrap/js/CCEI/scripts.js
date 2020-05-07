$(document).on("wb-ready.wb", function(event) {
  /*** HEADER ***/
  $("#header-srch>button").on("click", function() {
    $("#srch-bar").slideToggle(400, function() {
      $("#search-bar input").focus();
    });
  });

  /*** DISMISSABLE CONTENT ***/
  $(".btn-clear-all").click(function(e) {
    e.preventDefault();
    $(this)
      .parent("#filters")
      .children("a")
      .each(function() {
        $(this).remove();
      });
  });

  $(".ndm-facet-button-close i").click(function(e) {
    e.preventDefault();
    $(this)
      .parent()
      .remove();
  });

  /*** CAROUSEL ***/
  $(".carousel-s3 .nxt").on("click", function(e) {
    e.stopImmediatePropagation();
    const tabsWidth = $(".carousel-s3 .tabpanels").width();
    const scrollPos = $(".carousel-s3 .tabpanels").scrollLeft();
    $(".carousel-s3 .tabpanels").animate({
      scrollLeft: scrollPos + tabsWidth
    });
  });

  $(".carousel-s3 .prv").on("click", function(e) {
    e.stopImmediatePropagation();
    const scrollPos = $(".carousel-s3 .tabpanels").scrollLeft();
    const tabsWidth = $(".carousel-s3 .tabpanels").width();
    $(".carousel-s3 .tabpanels").animate({
      scrollLeft: scrollPos - tabsWidth
    });
  });

  $('.carousel-s3 div[role="tabpanel"]').on("click", function() {
    const scrollPos = $(".carousel-s3 .tabpanels").scrollLeft();
    const tabsWidth = $(".carousel-s3 .tabpanels").width();
    const tabPos = $(this).position().left + $(this).width();
    $('.carousel-s3 div[role="tabpanel"]').removeClass("in");
    $(this).removeClass("out");
    $(this).addClass("in");
    if (tabPos > tabsWidth) {
      $(".carousel-s3 .tabpanels").animate({
        scrollLeft: scrollPos + (tabPos - tabsWidth) + 15
      });
    } else if (tabPos < $(this).width()) {
      $(".carousel-s3 .tabpanels").animate({
        scrollLeft: scrollPos - ($(this).width() - tabPos)
      });
    }
  });

  // DATEPICKER
  if ($("#startdate").length != 0) {
    $("#startdate, #enddate")
      .datepicker({
        dateFormat: "yy-mm-dd",
        constrainInput: true,
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: "../assets/calendar.png",
        buttonImageOnly: true,
        showButtonPanel: true
      })
      .mask("9999-99-99");
  }

  // SEARCH PAGE TABBED INTERFACE RESPONSIVENESS
  for (let i = 1; i < 5; i++) {
    $("#search-panel" + i + "-lnk").click(function() {
      if ($(window).width() < 992) {
        for (let x = 1; x < 5; x++) {
          $("#search-panel" + x)
            .removeAttr("open")
            .removeClass("open")
            .attr("aria-selected", false);
          $("#search-panel" + x + "-lnk")
            .parent("li")
            .removeClass("active");
          $("#search-panel" + x + "-lnk").attr("aria-selected", false);
        }
        $(this)
          .parent("li")
          .addClass("active");
        $(this).attr("aria-selected", true);
        $("#search-panel" + i)
          .addClass("open")
          .attr("open", "open");
      }
    });
  }

  // NAV MENU
});
