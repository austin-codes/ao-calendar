<?php

add_action( 'admin_menu', 'register_my_custom_menu_page' );

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

function ao_cal_render_admin_menu() {
    ob_start();

    ao_cal_render_admin_header();

    $output = ob_get_contents();
    ob_end_flush();

    return $output;
}

function ao_cal_render_admin_header() {
    ?>
    <h1>AO Calendar</h1>
    <h6>The first plugin (of hopefully many) created and released by Alpha Omega Development.</h6>
    <p>The most important thing is the shortcode to display the calendar. That code is [ao-calendar].</p>
    <?php
}
