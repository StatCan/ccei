(function ($, Drupal) {
  Drupal.behaviors.cceiIndicators = {
    attach: function (context, settings) {
      $('.ccei-indicators .ind-nav', context).once('cceiIndicators').each(function () {
        const wrapper = $(this);
        const indList = wrapper.parent().find('.ind-list');
        const translateX = 220 + 10 // Width + margin-right.
        const indCount = indList.children().length
        let steps = 0 // Number of indicators to shift.

        wrapper.find('.btn-ind').on('click', function (e) {
          if (e.target.name === 'prev') {
            if (steps > 0) {
              steps--
            }
            if (steps <= 0) {
              $(this).addClass('disabled')
              $(this).attr('aria-disabled', true)
            }
            else if (steps < indCount - 1) {
              wrapper.find('.btn-ind.next').removeClass('disabled')
              wrapper.find('.btn-ind.next').attr('aria-disabled', false)
            }
          }
          else if (e.target.name === 'next') {
            if (steps < indCount - 1) {
              // TODO: calculate how many indicators can fit on screen at current time.
              //  and use that as limit instead.
              steps++
            }
            if (steps >= indCount - 1) {
              $(this).addClass('disabled')
              $(this).attr('aria-disabled', true)
            }
            else if (steps > 0) {
              wrapper.find('.btn-ind.prev').removeClass('disabled')
              wrapper.find('.btn-ind.prev').attr('aria-disabled', false)
            }
          }
          indList.css('transform', 'translateX(' + (steps * -translateX) + 'px)')
        });
      });
    }
  };
})(jQuery, Drupal);
