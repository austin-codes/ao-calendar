<?php

/**
* Class used to build and manipulate
* the AO Calendar event table
* @author Austin Adamson
* @author aodev
*
* @since 1.0.0
*/
class AOCalDB {

    private $sqltable = 'ao_cal_events';

    private $select = 'SELECT *';
    private $from = 'FROM wp_posts';
    private $where = '';

    private $statement = NULL;


    /**
    * Construct the table if it doesn't already exist.
    *
    * schema =
    * 		id              - Primary Key Auto Increment
    * 		title           - String title of the event
    * 		start_date      - Date of event start in YYYY-MM-DD format
    * 		end_date        - Date of event end in YYYY-MM-DD format
    * 		description     - The complete description of the event
    * 		location        - Address of the event location
    * 		categroy        -
    * 		source          -
    * 		alternate_id    -
    *
    * @since 1.0.0
    */
    public function __construct() {
        global $wpdb;
        $this->sqltable = $wpdb->prefix . $this->sqltable;
        $this->from = 'FROM ' . $this->sqltable;
        $table_check = $wpdb->get_var("SHOW TABLES LIKE '$this->sqltable'");
        if ($table_check != $this->sqltable) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $sql = "CREATE TABLE $this->sqltable (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        title text NOT NULL,
                        start_date text NOT NULL,
                        end_date text NOT NULL,
                        start_time text NOT NULL,
                        end_time text NOT NULL,
                        description longtext NOT NULL,
                        location text,
                        category text,
                        source text,
                        alternate_id text,
                        PRIMARY KEY (id)
                    )
                CHARACTER SET utf8
                COLLATE utf8_general_ci;";

            $sql = apply_filters('aocal-sql-table', $sql);
            dbDelta($sql);
        }
    }

    /**
     * CRUD methods for the database table
     */

    /**
     * Inserts information into database table
     * @param ARRAY $data Inputs for the row of the table
     *                   array(
     *                   	'title' => 'Title',
     *                    	'start_date' => 'MM-YYYY',
     *                     	'end_date' => 'MM-YYYY',
     *                      'start_time' => '-1',
     *                      'end_time' => '-1',
     *                      'description' => 'Description',
     *                      'location' => '-1',
     *                      'category' => '-1',
     *                      'alternate_id' => '-1',
     *                   )
     * @since 1.0.0
     * @return VOID
     */
    function add($data) {
        global $wpdb;
        $defaults = array(
            'title' => '-1',
            'start_date' => '-1',
            'end_date' => '-1',
            'start_time' => '-1',
            'end_time' => '-1',
            'description' => '-1',
            'location' => '-1',
            'category' => '-1',
            'alternate_id' => '-1',
        );

        $data = array_merge($defaults, $data);

        $row = array(
            'title' => $data['title'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'description' => $data['description'],
            'location' => $data['location'],
            'category' => $data['category'],
            'alternate_id' => $data['alternate_id'],
        );
        foreach ( $row as $key=>$val ) {
            $row[$key] = $this->filter_input( $val );
        }
        $wpdb->insert( $this->sqltable, $row );
    }

    /**
     * Retrieves desired information from the database table.
     * @param  [type] $id   ID of specific desired row
     * @param  BOOL $indi   Whether or not you want a single response
     * @param  [type] $str  [description]
     * @param  BOOL     $reset  Whether or not the query variables need to be reset
     * @return [type]       [description]
     */
    function get($id = NULL, $indi = FALSE, $str = NULL, $reset = TRUE) {
        global $wpdb;

        // If an ID is set then we know what row we want,
        // so we set the ID to be specifically that row.
        if (isset($id)) {
            $this->where('ID', $id);
        }

        // Build the statement.
        if (is_null($this->statement)) {
            $this->statement();
        }


        if ($indi) {
            $response = $wpdb->get_row($this->statement);
            if (is_null($response)) {return '';}
            if (count(get_object_vars($response)) == 1) {
                foreach ($response as $r) {
                    $result = $r;
                }
            }
            else {
                $result = $response;
            }
        }

        else {
            $result = $wpdb->get_results($this->statement);
        }


        if ($reset) {
            $this->reset();
        }
        $this->statement();

        return $result;
    }

    /**
     * Update row.
     * @param  INT/STRING $id   ID of row to be changed
     * @param  ARRAY $data The information to be updated
     * @return
     */
    function update($id, $data) {
        global $wpdb;

        if (!is_array($data)) {
            return;
        }
        $wpdb->update($this->sqltable, $data, array('id' => $id));
    }

    /**
     * Delete an entry at the database table
     * @param  INT/STRING $id ID of the row to be deleted
     * @return
     */
    function delete($id) {
        global $wpdb;
        $wpdb->delete($this->sqltable, array('id' => $id));
    }

    /**
     * Statement Creation
     */

    /**
     * Format the select portion of the SQL statement.
     * @param  STRING/ARRAY $select String with values or array of values to select
     * @return
     */
    function select($select) {

        $this->select = 'SELECT ';

        // Check to see if param is an array
        if (is_array($select)) {

            // Filter through elements of the array and appending to
            // select var
            foreach ($select as $item) {

                // If the count or the array - 1 is equal to the index of this element
                // then this element is the last and therefore doesn't require a
                // trailing comma.
                $last = (count($select) - 1 == array_search($item, $select));

                if ($last) {
                    $this->select .= $item;
                }
                else {
                    $this->select .= $item . ', ';
                }

            }
        }
        // Param wasn't an array, simply set the select var.
        else {
            $this->select .= $select;
        }
    }

    /**
     * Format the from join portions of the MySQL query
     *
     * TODO: Not thoroughly tested, however, it is also not used here.
     *
     * @param  [type] $left_table  [description]
     * @param  [type] $right_table [description]
     * @param  string $join        [description]
     * @return [type]              [description]
     */
    function from($left_table, $right_table = NULL, $on = NULL, $join = 'JOIN' ) {
        $f = 'FROM ';

        if ( !is_null($right_table) ) {
            $f .= $left_table . ' ' . $join . ' ' . $right_table . ' ON ' . $on;
        }
        else {
            $f .= $left_table . ' ';
        }

        $this->from = $f;
    }

    /**
     * Build the where portion of the MySQL query
     * @param  STRING/ARRAY $source String: Column. Array: View schema below.
     * @param  [type] $target [description]
     * @param  string $comp   [description]
     * @return [type]         [description]
     */
    function where($source, $target = NULL, $comp = '=') {
        if (is_array($source)) {
            /**
             * $source array schema:
             * array(
             * 		array($source, $target, $comp, $follow),
             * 		array($source, $target, $comp, $follow),
             * 		array($source, $target, $comp, $follow)
             * );
             *
             *
             */

            $sql = 'WHERE ';

            foreach ($source as $s) {

                // Make sure each element is an array
                if (is_array($s)) {
                    // Set the source of the sub array
                    $sql .= $s[0] . ' ';

                    // Is the comp val set in the sub array
                    if (isset($s[2])) {
                        $sql .= $s[2] . ' ';
                    }
                    else {
                        $sql .= '= ';
                    }

                    // set the target of the sub array
                    $sql .= $s[1] . ' ';


                    if (count($source) - 1 == array_search($s, $source)) {
                        $sql .= '';
                    }
                    else {
                        if ($target) {
                            $sql .= $target . ' ';
                        }
                        else {
                            $sql .= 'AND ';
                        }

                    }
                }
            }
        }

        else {

            $sql = 'WHERE ' . $source . ' ' . $comp . ' ' . $target . ' ';

        }

        $this->where = $sql;

    }

    /**
     * Format the statement
     * @param  [type] $stmt Optional statement to use.
     * @return [type]       [description]
     */
    function statement($stmt = NULL) {
        if (is_null($stmt)) {
            $this->statement = $this->select . ' ' . $this->from . ' ' . $this->where;
        }
        else {
            $this->statement = $stmt;
        }
        return $this->statement;
    }


    /**
     * Other Methods
     */

    /**
     * Cleanse input to prevent harmful additions to DB
     * @param  STRING $val Data to be put into the DB table
     * @since 1.0.0
     * @return STRING      Data cleansed to be added to DB table
     */
    function filter_input($val) {
        $input = esc_html($val);
        return $input;
    }

    /**
     * Reset class variables to defualts
     */
    function reset() {
        global $wpdb;
        $this->sqltable = $wpdb->prefix .  'ao_cal_events';
        $this->select = 'SELECT *';
        $this->from = 'FROM ' . $this->sqltable;
        $this->where = '';
        $this->statement = NULL;
    }
}
