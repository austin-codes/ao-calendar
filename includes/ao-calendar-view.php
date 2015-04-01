<?php
/**
 *
 *
 * @author Austin Adamson
 * @since 1.0.0
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
                <div class="event-start-date">
                    <strong>Start Date:</strong>
                    <?php echo $event->start_date; ?>
                </div>
                <div class="event-start-time">
                    <strong>Start Time:</strong>
                    <?php echo $event->start_time; ?>
                </div>
                <div class="event-end-date">
                    <strong>End Date:</strong>
                    <?php echo $event->end_date; ?>
                </div>
                <div class="event-end-time">
                    <strong>End Time:</strong>
                    <?php echo $event->end_time; ?>
                </div>
                <div class="event-description">
                    <strong>Description:</strong>
                    <?php echo $event->description; ?>
                </div>
            </div>
        </div>
        <?php
    }
    //dump($day);
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
