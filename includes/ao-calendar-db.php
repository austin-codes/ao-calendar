<?php

/**
* Class used to build and manipulate
* the AO Calendar event table
*/
class AOCalDB {

    private $sqltable = 'ao_cal_events';

    /**
    * Construct the table if it doesn't already exist.
    * @since 1.0.0
    */
    public function __construct() {
        global $wpdb;
        $this->sqltable = $wpdb->prefix . $this->sqltable;
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


    function get($id = NULL, $str = NULL) {
        

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
    function filter_inputs($val) {
        $input = esc_html($val);
        return $input;
    }
}
