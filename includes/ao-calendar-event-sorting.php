<?php
/**
 *
 *
 * @author Austin Adamson
 *
 * @since 1.0.0
 */


function ao_cal_sort_events($events, $m, $y) {
    // dump($m);
    // dump(mktime(0, 0, 0, $m, $y ));
    $first = getdate(mktime(0, 0, 0, $m, 1, $y ));
    // dump($first);

    $month = ao_cal_create_month( $m, $y );

    $events = ao_cal_sort_events_into_months($month, $events, $m, $y);

    return $events;
}

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

    //dump($fullMonth);

    return $fullMonth;

}
