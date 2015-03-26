<?php

add_action('admin_menu', 'ao_cal_register_event_list_submenu', 20);

/**
 * Adds the add new event submenu to the WordPress dashboard
 * @since 1.0.0
 */
function ao_cal_register_event_list_submenu() {
        add_submenu_page(
          'aocal-main',
          'Event List',
          'Event List',
          'manage_options',
          'aocal-event-list',
          'ao_cal_render_admin_event_list_submenu'
    );
    //dump($_POST);

}

/**
 * Render the HTML for the add event list submenu page
 * @since 1.0.0
 * @return STRING HTML output
 */
function ao_cal_render_admin_event_list_submenu() {
    ob_start();

    ao_cal_render_admin_header();
    ao_cal_render_admin_event_list_content();

    $output = ob_get_contents();
    ob_end_clean();

    echo $output;
}

/**
 * Render the content for the event list submenu page
 * @since 1.0.0
 * @return STRING HTML output
 */
function ao_cal_render_admin_event_list_content() {
    global $aocal;

    $events = $aocal->db->get();
    dump($events);
}
