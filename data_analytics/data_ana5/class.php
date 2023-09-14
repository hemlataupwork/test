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
        $columns = array('sno', 'coursename', 'classname', 'assignment', 'pencentage');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('S no.', 'Course name', 'Class name', 'Assignment name', 'Percentage of students receiving less than 50% for an assessment (by assessment)');
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


    function col_coursename($values)
    {
        session_start();
        $catname = $_SESSION['catname'];
        if ($this->is_downloading()) {

            return $catname;
        } else {
            return $catname;
        }
    }

    /**
     * This function is called for each data row to allow processing of
     * columns which do not have a *_cols function.
     * @return string return processed value. Return NULL if no change has
     *     been made.
     */
    function col_classname($values)
    {
        // var_dump($values);


        // If the data is being downloaded than we don't want to show HTML.
        if ($this->is_downloading()) {

            return $values->fullname;
        } else {
            return $values->fullname;
        }
    }
    function col_assignment($values)
    {
        if ($this->is_downloading()) {

            return $values->name;
        } else {
            return $values->name;
        }
    }
    function col_pencentage($values)
    {
        global $DB;
    

        // If the data is being downloaded than we don't want to show HTML.
        // Specify the course ID for which you want to get the total number of enrolled but never accessed students
    
        $graded=0;
        $losser=0;
        
        $context = context_course::instance($values->course);
        $users = get_enrolled_users($context);
        foreach($users as $user)
        {
            $role=$DB->get_record_sql("SELECT roleid from {role_assignments} where contextid='$context->id' and userid=$user->id");
            $is_student=$DB->get_record_sql("SELECT * from {role} where id='$role->roleid' and shortname='student'");
            
            if($is_student)
            {
                $is_graded=$DB->get_record_sql("SELECT * from {assign_grades} where assignment='$values->id' and userid=$user->id and grade > -1");
                if($is_graded)
                {
                    $graded++;
                    $a=($is_graded->grade/$values->grade)*100;
                    
                    if($a<50)
                    {
                        $losser++;
                    }
                    

                }
            }


        }
        $losser_percentage=0;
       
        if($graded!=0){
      
       $losser_percentage=($losser/$graded)*100;
       
        }

       

        



        if ($this->is_downloading()) {
            return (int)$losser_percentage . '%';
        } else {
            return (int)$losser_percentage . '%';
        }
    }
}
