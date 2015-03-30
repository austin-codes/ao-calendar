jQuery(function() {

    // When an event button is clicked execute function
    jQuery('.event-delete-button').on('click', function(e) {
        e.preventDefault();

        var url = window.location.href;     // Get this page's url
        var container = jQuery(this);       // Get this button
        var id = jQuery(this).attr('data-id');      // Get the ID to be removed

        // Make the AJAX call to delete the event from the DB
        jQuery.get( url, {
            'aoCalDeleteEvent' : 'alphaomegadevelopmentcalendar',
            'aoCalEventID' : id
        }, function (q) {
            console.log('Event deleted. Event ID: ' + q);
            var whileCatch = 0;
            while ((! container.hasClass('event-row')) && whileCatch < 10) {
                container = container.parent();
                whileCatch = whileCatch + 1;
            }
            container.remove();
        });
    });

});
