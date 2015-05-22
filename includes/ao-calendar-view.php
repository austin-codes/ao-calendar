<?php
/**
 * @author Austin Adamson
 * @since 1.0.0
 */


/**
 * Render the month display
 * @param  ARRAY $month Array of arrays simulating the month
 * @return STRING        [description]
 */
function aocal_render_calendar_month($month) {
    ob_start();
    ?>
    <div class="month">
        <?php
        foreach ($month as $week) {
            ?>
            <div class="week">
                <?php
                foreach ( $week as $day) {
                    ?>
                    <div class="day" data-day="<?php echo $day['date']; ?>">
                        <?php echo aocal_render_calendar_day($day); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

/**
 * HTML formatting for a single day of the calendar
 * @param  [type] $day [description]
 * @return [type]      [description]
 */
function aocal_render_calendar_day($day) {
    ob_start();
    if ($day['date'] != '0') {
        ?>
        <div class="date-display">
            <?php echo $day['date']; ?>
        </div>
        <?php
    }
    else {
        ?>
        <div class="empty-date-display"></div>
        <?php
    }
    foreach ($day['event-list'] as $event) {
        ?>
        <div class="event">
            <?php echo $event->title; ?>
            <div class="event-more-info">
                <?php
                /**
                 * Check if each aspect of the
                 * event is null, if not then
                 * lets display it appropriately.
                 */
                if (!is_null($event->start_date)) {
                    ?>
                    <div class="event-start-date">
                        <strong>Start Date:</strong>
                        <?php echo apply_filters( 'ao-cal-event-start-date-filter', $event->start_date); ?>
                    </div>
                    <?php
                }
                if (!is_null($event->start_time)) {
                    ?>
                    <div class="event-start-time">
                        <strong>Start Time:</strong>
                        <?php echo apply_filters( 'ao-cal-event-start-time-filter', $event->start_time); ?>
                    </div>
                    <?php
                }
                if (!is_null($event->end_date)) {
                    ?>
                    <div class="event-end-date">
                        <strong>End Date:</strong>
                        <?php echo apply_filters( 'ao-cal-event-end-date-filter', $event->end_date); ?>
                    </div>
                    <?php
                }
                if (!is_null($event->end_time)) {
                    ?>
                    <div class="event-end-time">
                        <strong>End Time:</strong>
                        <?php echo apply_filters( 'ao-cal-event-end-time-filter', $event->end_time); ?>
                    </div>
                    <?php
                }
                if (!is_null($event->location)) {
                    ?>
                    <div class="event-location">
                        <strong>Address:</strong>
                        <address><?php echo apply_filters( 'ao-cal-event-location-filter', $event->location); ?></address>
                    </div>
                    <?php
                }
                if (!is_null($event->description)) {
                    ?>
                    <div class="event-description">
                        <strong>Description:</strong>
                        <?php echo apply_filters( 'ao-cal-event-description-filter', $event->description); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
