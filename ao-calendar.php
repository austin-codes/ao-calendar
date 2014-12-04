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

function ao_cal_execute_admin() {
    require_once(plugin_url('/includes/ao-calendar-admin.php', __FILE__));
}

add_shortcode('ao-calendar', ao_cal_render_display());

function ao_cal_render_display() {
    $events = ao_cal_get_events();
    ob_start(); 
    ?>
    <div id="ao-cal-display-container" class="ao-cal-display-container">
        <div class="ao-cal-data" style="display: none;" data_ao_cal_data_=""></div>
        <div id="ao-cal-display" class="ao-cal_display"></div>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_flush();

    return $output;
}

function ao_cal_get_events() {
    // Retrieve all events from the database
}
