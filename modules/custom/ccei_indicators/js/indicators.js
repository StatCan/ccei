(function ($, Drupal) {
  Drupal.behaviors.cceiIndicators = {
    attach: function (context, settings) {
      $( document).on("wb-ready.wb", function ( e ) {
        $('.ccei-indicators .ind-nav', context).once('cceiIndicators').each(function () {
          const wrapper = $(this);
          const indList = wrapper.parent().find('.ind-list');
          const translateX = 220 + 10 // Width + margin-right.
          const indCount = indList.children().length
          let steps = 0 // Number of indicators to shift.

          wrapper.find('.btn-ind').on('click', function (e) {
            let stepsPerScreen = Math.floor((indList.width() - wrapper.width()) / translateX)
            if (e.target.name === 'prev') {
              if (steps > 0) {
                steps = Math.max(0, steps - stepsPerScreen)
              }
              if (steps <= 0) {
                $(this).addClass('disabled')
                $(this).attr('aria-disabled', true)
              }
              if (steps < indCount - 1) {
                wrapper.find('.btn-ind.next').removeClass('disabled')
                wrapper.find('.btn-ind.next').attr('aria-disabled', false)
              }
            }
            else if (e.target.name === 'next') {
              if (steps < indCount - 1) {
                steps = Math.min(indCount - stepsPerScreen, steps + stepsPerScreen)
              }
              if (steps >= indCount - stepsPerScreen) {
                $(this).addClass('disabled')
                $(this).attr('aria-disabled', true)
              }
              if (steps > 0) {
                wrapper.find('.btn-ind.prev').removeClass('disabled')
                wrapper.find('.btn-ind.prev').attr('aria-disabled', false)
              }
            }
            indList.css('transform', 'translateX(' + (steps * -translateX) + 'px)')
          });
        });
      });
    }
  };
})(jQuery, Drupal);
