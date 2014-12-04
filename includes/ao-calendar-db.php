<?php

/**
 * Class used to build and manipulate
 * the AO Calendar event table
 */
class AOCalDB {

    private $sqltable;

    /**
     * Construct the table if it doesn't already exist.
     */
    public function __construct() {
        global $wpdb;
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
}
