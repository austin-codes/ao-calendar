<?php

/**
 * An Abstract DB Table Class used for table interaction
 *
 * This is the more complete version of the
 * ao-calendar-db.php file. Rewriting inorder to
 * cooperate with WordPress wasn't in the
 * allowed timeframe for initial launch.
 *
 * If anyone is reading this and wishes to
 * continue translating, then feel free. If you
 * send me the updated files then I will add them.
 *
 * @author Austin Adamson
 * @author aodev.io 
 * @version 2.1.1
 */

abstract class DBTable {

    protected $name;

    protected $cols;

    protected $cols_array = array();

    private $select = 'SELECT *';
    private $from = 'FROM ';
    private $where = '';
    private $group = NULL;
    private $statement = NULL;

    public function __construct() {
        $this->from = 'FROM ' . $this->name;

        foreach ($this->cols as $col) {
            $this->cols_array[] = $col[0];
        }
        //dump($this->cols_array);

        $this->create_table();
    }

    public function create_table() {
        global $db;
        $db->create_table($this->name, $this->cols);
    }





    /**
     * Ripped from AO Cal
     */

     function add($data = array()) {
         global $db;
         $row = array();

         foreach ($this->cols as $col) {
                $name = $col['0'];
                $type = $col['1'];

                if (isset($data[$name])) {
                    $row[$name] = $data[$name];
                }
                else {
                    if (isset($this->default[$name])) {
                        $row[$name] = $this->default[$name];
                    }
                    else {
                        $row[$name] = -1;
                    }
                }
         }
        //  dump($this->cols);
        // dump($row, 'DATA');

         $insert = 'INSERT INTO ' . $this->name . ' (';
         $values = 'VALUES (';
         $index = 0;
         foreach ( $row as $key=>$val ) {
            //  dump($index);
             // $row[$key] = $this->filter_input( $val );


             if ( count($row) - 1 == $index ) {
                 $insert .= $key . ') ';
                 $values .= '"'. $val . '") ';
             }
             else {
                 $insert .= $key . ', ';
                 $values .= '"'. $val . '", ';
             }

             $index = $index + 1;
         }

         $db->query($insert . $values);
         return $db->getID();

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
         global $db;
        //  if (isset($id)) {
        //      $this->where('ID', $id);
        //  }

        if (is_null($this->statement)) {
            $this->statement();
        }
         //dump($this->statement);

         $result = $db->query($this->statement);

        //  if ($indi) {
        //      $response = $wpdb->get_row($this->statement);
        //      if (is_null($response)) {return '';}
        //      if (count(get_object_vars($response)) == 1) {
        //          foreach ($response as $r) {
        //              $result = $r;
        //          }
        //      }
        //      else {
        //          $result = $response;
        //      }
        //  }

        //  else {
        //
        //  }


         // dump($result);

         if ($reset) {
             $this->reset();
         }
         $this->statement();

        return $result;
     }

     function update($id, $data) {
         global $db;

         if (!is_array($data)) {
             return;
         }
         // $wpdb->update($this->sqltable, $data, array('id' => $id));

         $row = array();

         foreach ($this->cols as $col) {
                $name = $col['0'];
                $type = $col['1'];

                if (isset($data[$name])) {
                    $row[$name] = $data[$name];
                }
         }

         $sql = 'UPDATE ' . $this->name . ' SET ';
         $index = 0;
         foreach ($row as $c=>$v) {
             if (count($row) - 1 == $index) {
                 $sql .= $c .'="' . $v . '" ';
             }
             else {
                 $sql .= $c . '="' . $v . '",';
             }
             $index++;
         }
         $sql .= 'WHERE id = ' . $id;
         $db->query($sql);
     }

     function delete($id) {
         global $db;
         $sql = 'DELETE FROM ' . $this->name . ' WHERE id = "' . $id . '"';
         $db->query($sql);
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
         $cols = $this->cols_array;

         // Check to see if param is an array
         if (is_array($select)) {
            foreach ($select as $i=>$k) {
                 if ( !in_array($k, $cols) ) {
                     unset($select[$i]);
                 }
            }

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
             if (in_array($select, $cols)) {
                 $this->select .= $select;
             }
             else {
                 $this->select .= '*';
             }
         }
         return $select; // Return the expected columns
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
                         $sql .= '= "';
                     }

                     // set the target of the sub array
                     $sql .= $s[1] . '" ';


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

             $sql = 'WHERE ' . $source . ' ' . $comp . ' "' . $target . '"';

         }
         $this->where = $sql;

     }

     function group_by($group) {

         $cols = $this->cols_array;

         if ( in_array($group, $cols) ) {
             $this->group = 'GROUP BY ' . $group . ' ';
             return $group;
         }
         else {
             return FALSE;
         }
     }

     /**
      * [statement description]
      * @param  [type] $stmt [description]
      * @return [type]       [description]
      */
     function statement($stmt = NULL, $debug = FALSE) {
         if (is_null($stmt)) {
             $this->statement = $this->select . ' ' . $this->from . ' ' . $this->where . ' ' . $this->group;
         }
         else {
             $this->statement = $stmt;
         }
         if ($debug) {
             return $this->statement;
         }
         else {
             return '0';
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
         $this->select = 'SELECT *';
         $this->from = 'FROM ' . $this->name;
         $this->where = '';
         $this->statement = NULL;
         $this->group = NULL;
     }


     function get_cols() {
         return $this->cols_array;
     }
}
