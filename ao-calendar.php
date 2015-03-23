<?php
/**
 * Plugin Name: AO Calendar
 * Plugin URI:
 * Description: Creates a base calendar event system
 * Author: Austin Adamson
 * Version: 0.0.1
 * Author URI: http://austinleeadamson.com/
 */


/**
 * Block direct accesss to plugin directories
 */
defined('ABSPATH') or die("YOU SHALL NOT PASS");

/**
 * Define constants
 */
define( "AO_PLUGINS_PATH", plugin_dir_path(dirname(__FILE__)) );
define( "AO_CAL_DIR", AO_PLUGINS_PATH . 'ao-calendar/' );

/**
 * Require General Files
 */
require_once( AO_PLUGINS_PATH . 'ao-calendar/includes/ao-calendar-db.php' );
require_once( AO_PLUGINS_PATH . 'ao-calendar/ao-calendar-functions.php' );
require_once( ABSPATH . 'wp-includes/pluggable.php' );



/**
 * General Functions
 */

/**
 * Execute Admin Displys
 * @since 1.0.0
 */
function ao_cal_execute_admin() {
    // ----- Require the main admin page
    require_once( AO_PLUGINS_PATH . 'ao-calendar/includes/ao-calendar-admin.php' );

    // ----- Require submenus
    require_once( AO_PLUGINS_PATH . 'ao-calendar/includes/ao-calendar-new-event-submenu.php' );
}

/**
* Renders a container div, a hidden div to pass info
* into jQuery, and a div in which the calendar is to
* be displayed.
* @since 1.0.0
* @uses ao_cal_gather_events()
* @return STRING HTML output
*/
function ao_cal_render_display() {

    $events = ao_cal_gather_events();
    // ----- Allowing for future advancement
    $events = apply_filters('ao-cal-events', $events);

    // ----- Get the current month and year
    $month = date('m');
    $year = date('Y');

    ob_start();
    ?>
    <h3>Hey There</h3>
    <div id="ao-cal-display-container" class="ao-cal-display-container">
        <div id="ao-cal-display" class="ao-cal_display" data-month="<?php echo $month; ?>" data-year="<?php echo $year; ?>"></div>
    </div>
    <?php

    $output = ob_get_contents();
    ob_end_clean();

    // ----- Allowing for future advancement
    $output = apply_filters('ao-cal-render-display', $output);

    return $output;
}

/**
 *AO Cal Class
 */

class AOCal {

    var $db;

    function __construct() {
        $this->db = new AOCalDB();
    }
}

global $aocal;
$aocal = new AOCal();

/**
 * ----- ----- ----- ----- -----
 * Functionality for Admin Dashboard
 * ----- ----- ----- ----- -----
 */


/**
 * Check to see if the requested page is a nadmin page,
 * then let's make sure that the user is
 * actually logged in
 */
if ( is_admin() && is_user_logged_in() ) {

    ao_cal_execute_admin();
}

/**
 * ----- ----- ----- ----- -----
 * Functionality for the website
 * ----- ----- ----- ----- -----
 */

 /**
 * Shortcode used to display the calendar
 */
else {
    add_shortcode( 'ao-calendar', 'ao_cal_render_display' );
}
