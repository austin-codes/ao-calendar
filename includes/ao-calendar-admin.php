<?php

add_action( 'admin_menu', 'ao_cal_register_admin_menu', 15);

/**
 * Adds the main menu to the WordPress dashboard
 */
function ao_cal_register_admin_menu() {
    add_menu_page(
        'AOCal Main',
        'AO Calendar',
        'update_plugins',
        'aocal-main',
        ao_cal_render_admin_menu(),
        'dashicons-schedule',
        100,
    );
}

/**
 * Renders the HTML for the main admin page of AO Calendar
 * on the WordPress dashboard.
 * @return STRING HTML output
 */
function ao_cal_render_admin_menu() {
    ob_start();

    ao_cal_render_admin_header();
    ao_cal_render_admin_main_content();

    $output = ob_get_contents();
    ob_end_flush();

    return $output;
}

/**
 * Renders HTML output for the header of AO Calendar admin pages.
 * @return STRING HTML output
 */
function ao_cal_render_admin_header() {
    ?>
    <h1>AO Calendar</h1>
    <h6>The first plugin (of hopefully many) created and released by Alpha Omega Development.</h6>
    <?php
}

/**
 * Renders HTML output for the content of the AO Calendar main admin page.
 * @return STRING HTML output
 */
function ao_cal_render_admin_main_content() {
    ?>
    <p>The most important thing is the shortcode to display the calendar. That code is [ao-calendar].</p>
    <?php
}

/**
 * Require Sub Menu 
 */
