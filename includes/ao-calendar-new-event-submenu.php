<?php

add_action('admin_menu', 'ao_cal_register_new_event_submenu', 20);

/**
 * Adds the add new event submenu to the WordPress dashboard
 * @since 1.0.0
 */
function ao_cal_register_new_event_submenu() {
        add_submenu_page(
          'aocal-main',
          'Add New Event',
          'Add New Event',
          'manage_options',
          'aocal-add-new-event',
          'ao_cal_render_admin_add_new_submenu'
    );

}

/**
 * Render the HTML for the add new event submenu page
 * @since 1.0.0
 * @return STRING HTML output
 */
function ao_cal_render_admin_add_new_submenu() {
    ob_start();

    ao_cal_render_admin_header();
    ao_cal_render_admin_add_new_content();

    $output = ob_get_contents();
    ob_end_clean();

    echo $output;
}

/**
 * Render the content for the add new submenu page
 * @since 1.0.0
 * @return STRING HTML output
 */
function ao_cal_render_admin_add_new_content() {
    ao_cal_render_admin_add_new_form();
}


/**
 * [ao_cal_render_admin_add_new_form description]
 * @since 1.0.0
 */
function ao_cal_render_admin_add_new_form() {
    ?>
    <form id="ao-cal-add-new-event-form" action="" method="post">
        <p>Enter inputs here</p>
        <table>
            <tr>
                <td>
                    <label for="ao-cal-new-event-title" class="ao-cal-new-event-label">Title</label>
                </td>
                <td>
                    <input type="text" name="ao-cal-new-event-title" class="ao-cal-new-event-input ao-cal-new-event-text-input">
                </td>
            </tr>

            <tr>
                <td>
                    <label for="ao-cal-new-event-start-date" class="ao-cal-new-event-label"></label>
                </td>
                <td>
                    <input type="date" name="ao-cal-new-event-start-date" class="ao-cal-new-event-input ao-cal-new-event-text-input">
                </td>
            </tr>

            <tr>
                <td>
                    <label></label>
                </td>
                <td>
                    <input>
                </td>
            </tr>
        </table>
        <input type="submit" name="new-event-submit" value="Add Event">
    </form>
    <?php
}

/**
 * [ao_cal_render_admin_add_new_form_table_row description]
 * @param {[type]} $label [description]
 * @param {[type]} $name  [description]
 * @param {[type]} $type  [description]
 * @since 1.0.0
 */
function ao_cal_render_admin_add_new_form_table_row($label, $name, $type) {
    $edit_label = strtolower($label);
    $edit_label = str_replace(' ', '-', $label);
    ?>
    <tr>
        <td>
            <label for="ao-cal-new-event-<?php echo $edit_label; ?>" class="ao-cal-new-event-label"><?php echo $label; ?></label>
        </td>
        <td>
            <input type="text" name="ao-cal-new-event-title" class="ao-cal-new-event-input ao-cal-new-event-text-input">
        </td>
    </tr>
    <?php
}
