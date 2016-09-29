<?php
/**
 * Generic Functions for the AO Calendar
 *
 * @author Austin Adamson AO Development
 * @since 1.0.0
 */


/**
* dump function for debug
*/
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE) {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left; width: 100% !important; font-size: 12px !important;">' . $label . ' => ' . $output . '</pre>';
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}
if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);exit;
    }
}







/**
 * Filters
 */


add_filter( 'aocal-month-display', 'ao_cal_change_month_display');

function ao_cal_change_month_display($mon) {
    $monthName = date('F', mktime(0, 0, 0, $mon, 10));
    return $monthName;
}


add_filter('aocal-date-divide-display', 'ao_cal_change_date_divide_display');

function ao_cal_change_date_divide_display($d) {
    return ' ';
}

add_filter('ao-cal-event-start-time-filter', 'ao_cal_render_time_display');
add_filter('ao-cal-event-end-time-filter', 'ao_cal_render_time_display');

function ao_cal_render_time_display($d) {
    $time_array = explode(':', $d);
    $h = $time_array[0];
    $m = $time_array[1];
    if ($h > 12) {
        $suf = 'PM';
        $h = intval($h) - 12;
    }
    else {
        $suf = 'AM';
    }
    return $h . ':' . $m . ' ' . $suf;
}



add_filter('ao-cal-event-start-date-filter', 'ao_cal_render_date_display');
add_filter('ao-cal-event-end-date-filter', 'ao_cal_render_date_display');

function ao_cal_render_date_display($date) {
    $date_array = explode('-', $date);
    $y = $date_array[0];
    $m = $date_array[1];
    $d = $date_array[2];

    return $d . ' ' . date('F', mktime(0, 0, 0, $m, 10)) . ' ' . $y;
}
