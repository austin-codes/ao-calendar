
/**
 * In the admin, this allows us to delete events.
 */
function aoCalEventDeleteButton() {

    jQuery('.event-delete-button').on('click', function(e) {
        e.preventDefault();

        var url = window.location.href;     // Get this page's url
        var container = jQuery(this);       // Get this button
        var id = jQuery(this).attr('data-id');      // Get the ID to be removed

        // Make the AJAX call to delete the event from the DB
        jQuery.get( url, {
            'aoCalDeleteEvent' : 'alphaomegadevelopmentcalendar',
            'aoCalEventID' : id
        }, function () {
            //console.log('Event deleted. Event ID: ' + q);
            var whileCatch = 0;
            while ((! container.hasClass('event-row')) && whileCatch < 10) {
                container = container.parent();
                whileCatch = whileCatch + 1;
            }
            container.remove();
        });

    });

}

/**
 * Here we add the functionality to view event details
 * when the calendar event is clicked.
 */
function aoCalAttachEvents() {
    jQuery('.event').on('click', function () {
        event.stopPropagation();
        jQuery('.event.active').removeClass('active');
        jQuery(this).addClass('active');
    });

    jQuery('html').on('click', function() {
        jQuery('.event.active').removeClass('active');
    });
}



function aoCalSetMonth() {
    var display = jQuery('#ao-cal-display');
    var mon = parseInt(display.attr('data-month'));
    var year = parseInt(display.attr('data-year'));
    var url = window.location.href;


    jQuery.get( url, {
        'aoCalRenderMonth' : 'alphaomegadevelopmentcalendar',
        'aoCalMonth' : mon,
        'aoCalYear' : year
    }, function (r) {
        display.html(JSON.parse(r));
        display.removeClass('loading');
        aoCalAttachEvents();
    });

    /**
     * Retrieve the formatting for the desired month
     */
    jQuery.get( url, {
        'aoCalGetMonth' : 'alphaomegadevelopmentcalendar',
        'aoCalMonth' : mon
    }, function (m) {
        jQuery('.ao-cal-month').html(m);
    });

    /**
     * Retrieve formatting for the desired year
     */
    jQuery.get( url, {
        'aoCalGetYear' : 'alphaomegadevelopmentcalendar',
        'aoCalYear' : year
    }, function (y) {
        jQuery('.ao-cal-year').html(y);
    });


}

/**
 * We have two buttons that act as controllers
 * for a calendar. A previous button as well
 * as a next button that navigate the months.
 */
function aoCalAttachCalendarControls() {
    jQuery('.prev-button').on('click', function(e) {
        e.preventDefault();
        var display = jQuery('#ao-cal-display');
        var mon = parseInt(display.attr('data-month'));
        var year = parseInt(display.attr('data-year'));
        mon = mon - 1;
        if (mon === 0) {
            mon = 12;
            year = year - 1;
        }
        display.html('<i class="fa fa-cog fa-spin"></i>').addClass('loading');
        display.attr('data-month', mon);
        display.attr('data-year', year);
        aoCalSetMonth();
    });

    jQuery('.next-button').on('click', function(e) {
        e.preventDefault();
        var display = jQuery('#ao-cal-display');
        var mon = parseInt(display.attr('data-month'));
        var year = parseInt(display.attr('data-year'));
        mon = mon + 1;
        if (mon === 13) {
            mon = 1;
            year = year + 1;
        }
        display.html('<i class="fa fa-cog fa-spin"></i>').addClass('loading');
        display.attr('data-month', mon);
        display.attr('data-year', year);
        aoCalSetMonth();
    });
}



jQuery(function() {

    aoCalEventDeleteButton();
    aoCalAttachCalendarControls();
    aoCalSetMonth();

});
