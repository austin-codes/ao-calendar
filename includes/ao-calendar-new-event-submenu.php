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
    //dump($_POST);

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
        <p>Add new event</p>
        <table>
            <?php
            ao_cal_render_admin_add_new_form_table_row('Title', 'event_title', 'text');
            ao_cal_render_admin_add_new_form_table_row('Start Date', 'event_start_date', 'date');
            ao_cal_render_admin_add_new_form_table_row('Start Time', 'event_start_time', 'time');
            ao_cal_render_admin_add_new_form_table_row('End Date', 'event_end_date', 'date');
            ao_cal_render_admin_add_new_form_table_row('End Time', 'event_end_time', 'time');
            ao_cal_render_admin_add_new_form_table_row('Description', 'event_description', 'textarea');
            ao_cal_render_admin_add_new_form_table_row('Address', 'event_address', 'text');
            ao_cal_render_admin_add_new_form_table_row('City', 'event_city', 'text');
            ao_cal_render_admin_add_new_form_table_row('State', 'event_state', 'text');
            ao_cal_render_admin_add_new_form_table_row('Zip', 'event_zip', 'text');
            ?>
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

    switch ($type) {
        case 'textarea':
            ao_cal_render_admin_add_textarea_form_table_row($label, $name);
            break;
        case 'date':
            ao_cal_render_admin_add_date_form_table_row($label, $name);
            break;
        case 'time':
            ao_cal_render_admin_add_time_form_table_row($label, $name);
            break;
        default:
            ao_cal_render_admin_add_text_form_table_row($label, $name);
    }
}


/**
* [ao_cal_render_admin_add_new_form_table_row description]
* @param {[type]} $label [description]
* @param {[type]} $name  [description]
* @param {[type]} $type  [description]
* @since 1.0.0
*/
function ao_cal_render_admin_add_text_form_table_row($label, $name) {
    $edit_label = strtolower($label);
    $edit_label = str_replace(' ', '-', $label);
    ?>
    <tr>
        <td>
            <label for="ao-cal-new-event-<?php echo $edit_label; ?>" class="ao-cal-new-event-label"><?php echo $label; ?></label>
        </td>
        <td>
            <input type="text" name="<?php echo $name; ?>" class="ao-cal-new-event-input ao-cal-new-event-text-input">
        </td>
    </tr>
    <?php
}

/**
* [ao_cal_render_admin_add_new_form_table_row description]
* @param {[type]} $label [description]
* @param {[type]} $name  [description]
* @param {[type]} $type  [description]
* @since 1.0.0
*/
function ao_cal_render_admin_add_date_form_table_row($label, $name) {
    $edit_label = strtolower($label);
    $edit_label = str_replace(' ', '-', $label);
    ?>
    <tr>
        <td>
            <label for="ao-cal-new-event-<?php echo $edit_label; ?>" class="ao-cal-new-event-label"><?php echo $label; ?></label>
        </td>
        <td>
            <input type="date" name="<?php echo $name; ?>" class="ao-cal-new-event-input ao-cal-new-event-date-input">
        </td>
    </tr>
    <?php
}

/**
* [ao_cal_render_admin_add_new_form_table_row description]
* @param {[type]} $label [description]
* @param {[type]} $name  [description]
* @param {[type]} $type  [description]
* @since 1.0.0
*/
function ao_cal_render_admin_add_time_form_table_row($label, $name) {
    $edit_label = strtolower($label);
    $edit_label = str_replace(' ', '-', $label);
    ?>
    <tr>
        <td>
            <label for="ao-cal-new-event-<?php echo $edit_label; ?>" class="ao-cal-new-event-label"><?php echo $label; ?></label>
        </td>
        <td>
            <input type="time" name="<?php echo $name; ?>" class="ao-cal-new-event-input ao-cal-new-event-time-input">
        </td>
    </tr>
    <?php
}

/**
* [ao_cal_render_admin_add_new_form_table_row description]
* @param {[type]} $label [description]
* @param {[type]} $name  [description]
* @param {[type]} $type  [description]
* @since 1.0.0
*/
function ao_cal_render_admin_add_textarea_form_table_row($label, $name) {
    $edit_label = strtolower($label);
    $edit_label = str_replace(' ', '-', $label);
    ?>
    <tr>
        <td>
            <label for="ao-cal-new-event-<?php echo $edit_label; ?>" class="ao-cal-new-event-label"><?php echo $label; ?></label>
        </td>
        <td>
            <textarea name="<?php echo $name; ?>" class="ao-cal-new-event-input ao-cal-new-event-textarea-input"></textarea>
        </td>
    </tr>
    <?php
}


/**
 *
 *
 *
 *
 */
 global $aocal;
 if (isset($_POST['new-event-submit']) && $_POST['new-event-submit'] === 'Add Event') {
     $new_event = array(
         'title' => $_POST['event_title'],
         'start_date' => $_POST['event_start_date'],
         'end_date' => $_POST['event_end_date'],
         'start_time' => $_POST['event_start_time'],
         'end_time' => $_POST['event_end_time'],
         'description' => $_POST['event_description'],
         'location' => $_POST['event_address'] . ', ' . $_POST['event_city'] . ',' . $_POST['event_state'] . ' ' . $_POST['event_zip'],
     );
     $aocal->db->add($new_event);
 }
