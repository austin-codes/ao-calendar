<?php

/**
* Class used to build and manipulate
* the AO Calendar event table
*/
class AOCalDB {

    private $sqltable = 'ao_cal_events';

    private $select = 'SELECT *';
    private $from = 'FROM wp_posts';
    private $where = '';

    private $statement = '';


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
                        location text NOT NULL,
                        category text NOT NULL,
                        source text NOT NULL,
                        alternate_id text NOT NULL,
                        PRIMARY KEY (id)
                    )
                CHARACTER SET utf8
                COLLATE utf8_general_ci;";

            $sql = apply_filter('aocal-sql-table', $sql);
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
     * [get description]
     * @param  [type] $id   ID of specific desired row
     * @param  BOOL $indi   Whether or not you want a single response
     * @param  [type] $str  [description]
     * @param  BOOL     $reset  Whether or not the query variables need to be reset
     * @return [type]       [description]
     */
    function get($id = NULL, $indi = FALSE, $str = NULL, $reset = TRUE) {
        global $wpdb;
        if (isset($id)) {
            $this->where('ID', $id);
        }
        $this->statement();
        // dump($this->statement);

        if ($indi) {
            $response = $wpdb->get_row($this->statement);
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


        // dump($result);

        if ($reset) {
            $this->reset();
        }
        $this->statement();
        // dump($this->statement);

        return $result;
    }

    function update($id, $data) {
        global $wpdb;

        if (!is_array($data)) {
            return;
        }
        $wpdb->update($this->sqltable, $data, array('ID' => $id));
    }

    function delete($id) {
        global $wpdb;
        $wpdb->delete($this->sqltable, array('ID' => $id));
    }

    /**
     * Statement Creation
     */

    /**
     * [select description]
     * @param  [type] $select [description]
     * @return [type]         [description]
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
     * [from description]
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
     * [where description]
     * @param  [type] $source [description]
     * @param  [type] $target [description]
     * @param  string $comp   [description]
     * @return [type]         [description]
     */
    function where($source, $target = NULL, $comp = '=') {
        if (is_array($source)) {
            /**
             * $source array schema:
             * array(
             * 		array($source, $target, $comp),
             * 		array($source, $target, $comp),
             * 		array($source, $target, $comp)
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
                        $sql .= 'AND ';
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
     * [statement description]
     * @param  [type] $stmt [description]
     * @return [type]       [description]
     */
    function statement($stmt = NULL) {
        if (is_null($stmt)) {
            $this->statement = $this->select . ' ' . $this->from . ' ' . $this->where;
        }
        else {
            $this->statement = $stmt;
        }
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

    function reset() {
        global $wpdb;
        $this->sqltable = $wpdb->prefix .  'ao_cal_events';
        $this->select = 'SELECT *';
        $this->from = 'FROM ' . $this->sqltable;
        $this->where = '';
        $this->statement = '';
    }
}
