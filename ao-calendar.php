<?php
/**
 * Plugin Name: AO Calendar
 * Plugin URI:
 * Description: Creates a base calendar event system
 * Author: aodev.io
 * Version: 1.0.2
 * Author URI: http://plugins.aodev.io
 */


/**
 * Block direct accesss to plugin directories
 */
defined('ABSPATH') or die("YOU SHALL NOT PASS");

/**
 * Define constants
 */

// Store filesystem path to this plugin's directory
define( "AO_DEV_CAL_DIR", plugin_dir_path(__FILE__) );

/**
 * Require General Files
 */
require_once( AO_DEV_CAL_DIR . 'includes/ao-calendar-db.php' );
require_once( AO_DEV_CAL_DIR . 'includes/ao-calendar-event-sorting.php' );
require_once( AO_DEV_CAL_DIR . 'includes/ao-calendar-view.php' );
require_once( AO_DEV_CAL_DIR . 'ao-calendar-functions.php' );
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
    require_once( AO_DEV_CAL_DIR . 'includes/ao-calendar-admin.php' );

    // ----- Require submenus
    require_once( AO_DEV_CAL_DIR . 'includes/ao-calendar-new-event-submenu.php' );
    require_once( AO_DEV_CAL_DIR . 'includes/ao-calendar-event-list-submenu.php' );
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

    // ----- Get the current month and year
    $month = date('m');
    $year = date('Y');

    $fullMonth = ao_cal_gather_events($month, $year);
    // ----- Allowing for future advancement
    $fullMonth = apply_filters('ao-cal-display-month', $fullMonth);

    ob_start();
    ?>
    <div id="ao-cal-display-container" class="ao-cal-display-container <?php echo apply_filters('ao-cal-container-class', ''); ?>">
        <div class="ao-cal-header">
            <div class="prev-button-container">
                <button name="prev-button" class="prev-button"><?php echo apply_filters('aocal-prev-button', '<<'); ?></button>
            </div>
            <div class="ao-cal-date">
                <p>
                    <span class="ao-cal-month" data-month="<?php echo $month; ?>"></span>
                    <span class="ao-cal-date-divide"><?php echo apply_filters( 'aocal-date-divide-display', '-'); ?></span>
                    <span class="ao-cal-year"  data-year="<?php echo $year; ?>"></span>
                </p>
            </div>
            <div class="next-button-container">
                <button name="next-button" class="next-button"><?php echo apply_filters('aocal-next-button', '>>'); ?></button>
            </div>
        </div>
        <!-- <div id="ao-cal-display" class="ao-cal-display loading" data-month="<?php echo $month; ?>" data-year="<?php echo $year; ?>"><i class="fa fa-cog fa-spin"></i></div> -->
        <div id="ao-cal-display" class="ao-cal-display loading" data-month="<?php echo $month; ?>" data-year="<?php echo $year; ?>"><?php echo ao_cal_render_calendar_month($fullMonth); ?></div>
    </div>
    <?php

    $output = ob_get_contents();
    ob_end_clean();

    // ----- Allowing for future advancement
    $output = apply_filters('ao-cal-render-display', $output);

    return $output;
}

/**
 * Styles and Scripts
 */

add_action( 'wp_enqueue_scripts', 'ao_cal_enqueue_scripts_and_styles');
add_action( 'admin_enqueue_scripts', 'ao_cal_enqueue_scripts_and_styles');

function ao_cal_enqueue_scripts_and_styles() {
    ao_cal_enqueue_styles();
    ao_cal_enqueue_scripts();
}

/**
 * Enqueue the CSS doc, and apply a filter that allows for
 * future users to enqueue styles here as well.
 */
function ao_cal_enqueue_styles() {
    wp_register_style( 'aocal-styles', plugins_url( 'css/styles.css', __FILE__ ) );
    wp_register_style( 'aocal-font-awesome-styles', plugins_url( 'scss/font-awesome/css/font-awesome.min.css', __FILE__ ) );

    $styles = array('aocal-styles', 'aocal-font-awesome-styles');
    $styles = apply_filters( 'ao-cal-enqueue-styles', $styles );
    foreach ($styles as $style) {
        wp_enqueue_style( $style );
    }
}

/**
 * Enqueue the JS doc, and apply a filter that allows for
 * future users to enqueue scripts here as well.
 */
function ao_cal_enqueue_scripts(){
    wp_register_script( 'aocal-scripts', plugins_url( 'js/script.min.js', __FILE__ ), array ('jquery'), false, false );

    $scripts = array( 'aocal-scripts' );
    $scripts = apply_filters( 'ao-cal-enqueue-scripts', $scripts );
    foreach ($scripts as $script) {
        wp_enqueue_script( $script );
    }
}

/**
 *AO Cal Class
 */

class AOCal {

    var $db;

    function __construct() {
        $this->db = new AOCalDB();
        $this->db->select(array('start_time', 'end_time'));
        $this->db->get(NULL, TRUE);
    }
}

global $aocal;
$aocal = new AOCal();

/**
 * Message to be given upon error
 */
global $dieMessage;
$dieMessage = '';

/**
 * Function used to kill pages
 * and give errors or ajax responses
 */
function ao_cal_eDie() {
    global $dieMessage;
    die($dieMessage);
}

/**
 * ----- ----- ----- ----- -----
 * Functionality for Admin Dashboard
 * ----- ----- ----- ----- -----
 */


/**
 * Check to see if the requested page is an admin page,
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


else if (isset($_GET['aoCalRenderMonth']) && $_GET['aoCalRenderMonth'] === 'alphaomegadevelopmentcalendar') {


    if ( isset($_GET['aoCalMonth']) && isset($_GET['aoCalYear']) ) {
        $month = intval($_GET['aoCalMonth']);
        $year = intval($_GET['aoCalYear']);

        if ($month > 12 || $month < 0) {
            die('Invalid Month Sent.');
        }
        else if ($month === 0) {
            if (is_user_logged_in()) {
                $dieMessage .= 'Either you are trying to get events from month 0, or you are trying to do sql injections via $_GET. Either way, stop it!';
            }
        }

        if ($year > 9999) {
            if (is_user_logged_in()) {
                $dieMessage .= 'Is it the year 10,000 already?';
            }
        }
        else if ($year === 0) {
            if (is_user_logged_in()) {
                $dieMessage .= 'Either you are trying to get events from year 0, or you are trying to do sql injections via $_GET. Either way, stop it!';
            }
        }
        else if ($year < 0) {
            if (is_user_logged_in()) {
                $dieMessage .= 'You have events in negative years?';
            }
        }

        $fullMonth = ao_cal_gather_events($month, $year);
        $fullMonth = apply_filters('ao-cal-display-month', $fullMonth);

        $dieMessage = json_encode(ao_cal_render_calendar_month($fullMonth));
    }

    remove_all_actions('init');
    add_action('init', 'ao_cal_eDie');
}


else if (isset($_GET['aoCalGetMonth']) && $_GET['aoCalGetMonth'] === 'alphaomegadevelopmentcalendar') {

    $month = intval($_GET['aoCalMonth']);
    $month = apply_filters('aocal-month-display', $month);
    $dieMessage .= $month;

    remove_all_actions('init');
    add_action('init', 'ao_cal_eDie');

}


else if (isset($_GET['aoCalGetYear']) && $_GET['aoCalGetYear'] === 'alphaomegadevelopmentcalendar') {

    $year = intval($_GET['aoCalYear']);
    $year = apply_filters('aocal-year-display', $year);

    $dieMessage .= $year;

    remove_all_actions('init');
    add_action('init', 'ao_cal_eDie');

}

 /**
 * Shortcode used to display the calendar
 */
else {
    add_shortcode( 'ao-calendar', 'ao_cal_render_display' );
}
