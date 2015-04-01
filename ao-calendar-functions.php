<?php
/**
 * Generic Functions for the AO Calendar
 *
 * @author Austin Adamson AO Development
 * @since 1.0.0
 */







/**
 * Gathers all events and returns them as a JSON string.
 * @since 1.0.0
 * @return STRING JSON string on events
 */
function ao_cal_gather_events($m, $y) {
    global $aocal;

    if ($m < 10) { $m = '0' . $m; }

    $where = array(
        array( 'start_date', '"' . $y . '-' . $m . '%"', 'LIKE' ),
        array( 'end_date', '"' . $y . '-' . $m . '%"', 'LIKE' )
    );
    $where = apply_filters( 'aocal-gather-events-where-filter', $where );

    $comp = 'OR';
    $comp = apply_filters( 'aocal-gather-events-comp-filter', $comp );


    // Retrieve all events from the database
    $aocal->db->where( $where, $comp);

    $events = $aocal->db->get();

    $events = apply_filters( 'aocal-events-filter', $events);
    return ao_cal_sort_events($events, $m, $y);
}



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
            echo $output;}else {return $output;}
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


add_filter( 'aocal-month-display', 'aocal_change_month_display');

function aocal_change_month_display($mon) {
    $monthName = date('F', mktime(0, 0, 0, $mon, 10));
    return $monthName;
}


add_filter('aocal-date-divide-display', 'aocal_change_date_divide_display');

function aocal_change_date_divide_display($d) {
    return ' ';
}
