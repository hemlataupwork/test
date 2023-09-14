<?php
// require_once('/path/to/moodle/config.php');


/**
 * Test table class to be put in test_table.php of root of Moodle installation.
 *  for defining some custom column names and proccessing
 * Username and Password feilds using custom and other column methods.
 */
class test_table extends table_sql
{

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid)
    {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('sno', 'firstname', 'lastname', 'timespend');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('S no.', 'First name', 'Last name', 'Time spend');
        $this->define_headers($headers);
    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * @return $string Return username with link to profile or username only
     *     when downloading.
     */

    function col_sno($values)
    {
        
        $sno = (($_GET['page']) * 10) + $values->num;

        // If the data is being downloaded than we don't want to show HTML.
        if ($this->is_downloading()) {

            return $sno;
        } else {
            return $sno;
        }
    }


    function col_firstname($values)
    {
        global $DB;
        $userid=$values->id;
        
        $user=$DB->get_record_sql("SELECT firstname from {user} where id='$userid'");

        if ($this->is_downloading()) {

            return $user->firstname;
        } else {
            return $user->firstname;
        }
    }

    /**
     * This function is called for each data row to allow processing of
      columns which do not have a _cols function.
     * @return string return processed value. Return NULL if no change has
     *     been made.
     */
    function col_lastname($values)
    {
        global $DB;
        $userid=$values->id;
        
        $user=$DB->get_record_sql("SELECT lastname from {user} where id='$userid'");




        // // If the data is being downloaded than we don't want to show HTML.
        if ($this->is_downloading()) {

            return $user->lastname;
        } else {
            return $user->lastname;
        }
    }
    function col_timespend($values)
    {
        global $DB;
     
        session_start();
        $startdate = $_SESSION['startdate'];
        $enddate = $_SESSION['enddate'];
        // var_dump($startdate);
        // var_dump($enddate);

        $total_time=$DB->get_records_sql("SELECT * from {teacher_timespend} where userid=$values->id and date BETWEEN UNIX_TIMESTAMP('$startdate') AND UNIX_TIMESTAMP('$enddate')");
        $count_time=0;
        foreach($total_time as $time)
        {
            $count_time=$count_time+$time->timespend;
        }
        $min=$count_time/60;;
        $hr=$min/60;
        $min=$min-((int)$hr*60);
        

       
      
       

        if ($this->is_downloading()) {

            return (int)$hr.'H : '.(int)$min.'MIN';
        } else {
            return  (int)$hr.'H : '.(int)$min.'MIN';
        }
    }

}