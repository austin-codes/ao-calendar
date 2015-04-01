


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
        //console.log('Query Made');
        display.html(JSON.parse(r));
        display.removeClass('loading');
    });


    jQuery.get( url, {
        'aoCalGetMonth' : 'alphaomegadevelopmentcalendar',
        'aoCalMonth' : mon
    }, function (m) {
        jQuery('.ao-cal-month').html(m);
    });

    jQuery.get( url, {
        'aoCalGetYear' : 'alphaomegadevelopmentcalendar',
        'aoCalYear' : year
    }, function (y) {
        jQuery('.ao-cal-year').html(y);
    });
}

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
