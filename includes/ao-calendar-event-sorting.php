<?php
/**
 *
 *
 * @author Austin Adamson
 *
 * @since 1.0.0
 */


function ao_cal_sort_events($events, $m, $y) {
    $first = getdate(mktime(0, 0, 0, $m, 1, $y ));
    $month = ao_cal_create_month( $m, $y );
    $events = ao_cal_sort_events_into_months($month, $events, $m, $y);
    return $events;
}

/**
 * Skeleton building. Basically we are creating
 * an array of arrays that mimic the format of
 * the desired month.
 *
 * @param  INT $m The two digit representation of the desired month
 * @param  INT $y The four digit representation of the desired year
 * @return ARRAY    The array of arrays.
 */
function ao_cal_create_month( $m, $y ) {

    $week = array();
    $month = array();

    $first = getdate(mktime(0, 0, 0, $m, 1, $y ));

    $lengthOfMonth = cal_days_in_month(CAL_GREGORIAN, $m, $y);

    for ( $i = 1; $i < $first['wday']; $i++) {
        $week[] = 0;
    }

    for ( $i = 1; $i <= $lengthOfMonth; $i++) {
        $day = getdate(mktime(0, 0, 0, $m, $i, $y ));
        $week[] = $day['mday'];

        if (count($week) == 7) {
            $month[] = $week;
            $week = array();
        }
    }
    $month[] = $week;

    $month = apply_filters( 'aocal-month-filter', $month);
    return $month;
}


/**
 * Gathers all events and returns them as a JSON string.
 * @since 1.0.0
 *
 * @param  INT $m The two digit representation of the desired month
 * @param  INT $y The four digit representation of the desired year
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
 * Takes the array of the desired constructed month skeleton and
 * the array of events and sorts the events into appropriate
 * dates.
 *
 * @param  ARRAY $month  The returned array from ao_cal_create_month
 * @param  ARRAY $events The returned array from ao_cal_gather_events
 * @param  INT $m The two digit representation of the desired month
 * @param  INT $y The four digit representation of the desired year
 * @return ARRAY        Month containing all events sorted properly
 */
function ao_cal_sort_events_into_months($month, $events, $m, $y) {

    $fullMonth = array();

    foreach ( $month as $week ) {

        if (is_array($week)) {

            $fullWeek = array();

            foreach ( $week as $day ) {

                if ($day > 9) {
                    $date = $y . '-' . $m . '-' .  $day;
                }
                else {
                    $date = $y . '-' . $m . '-0' .  $day;
                }

                $day = array(
                    'date' => $day,
                    'event-list' => array()
                );

                foreach ( $events as $event ) {

                    if ($event->start_date <= $date && $event->end_date >= $date ) {
                        $inputEvent = apply_filters( 'aocal-input-event-filter', $event);
                            $day['event-list'][] = $inputEvent;
                    }

                }

                $fullWeek[] = $day;
            }

            $fullMonth[] = $fullWeek;
        }
    }

    return $fullMonth;

}
