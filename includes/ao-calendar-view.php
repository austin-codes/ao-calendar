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
    ?>

    <div class="date-display">
        <?php if ($day['date'] != '0') {echo $day['date'];} ?>
    </div>

    <?php

    foreach ($day['event-list'] as $event) {
        ?>
        <div class="event">
            <?php echo $event->title; ?>
        </div>
        <?php
    }
    //dump($day);
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
