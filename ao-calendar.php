<?php
/**
 * Plugin Name: AO Calendar
 * Plugin URI:
 * Description: Creates a base calendar system event system
 * Author: AODev
 * Version: 0.0.1
 * Author URI:
 */

if (is_admin()) {
    ao_cal_execute_admin();
}

/**
 * Render the admin pages
 */
function ao_cal_execute_admin() {
    require_once(plugin_url('/includes/ao-calendar-admin.php', __FILE__));
}


/**
 * Shortcode used to display the calendar
 */
add_shortcode('ao-calendar', ao_cal_render_display());

/**
 * Renders a container div, a hidden div to pass info
 * into jQuery, and a div in which the calendar is to
 * be displayed.
 * @return STRING HTML output
 */
function ao_cal_render_display() {
    $events = ao_cal_gather_events();
    $events = apply_filters('ao-cal-events', $events);
    ob_start(); 
    ?>
    <div id="ao-cal-display-container" class="ao-cal-display-container">
        <div class="ao-cal-data" style="display: none;" data_ao_cal_data_=""></div>
        <div id="ao-cal-display" class="ao-cal_display"></div>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_flush();

    $output = apply_filters('ao-cal-render-display', $output);

    return $output;
}

/**
 * Gathers all events and returns them as a JSON string.
 * @return STRING JSON string on events
 */
function ao_cal_gather_events() {
    // Retrieve all events from the database
    return '';
}
