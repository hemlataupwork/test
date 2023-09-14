<?php

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
        $columns = array('sno', 'coursename', 'classname', 'pencentage');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('S no.', 'Course name', 'Class name', 'Percentage of students who never log in from any class');
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

        // If the data is being downloaded than we don't want to show HTML.
        if ($this->is_downloading()) {

            return $values->fullname;
        } else {
            return $values->fullname;
        }
    }
    function col_pencentage($values)
    {
        // If the data is being downloaded than we don't want to show HTML.
        // Specify the course ID for which you want to get the total number of enrolled but never accessed students
        global $DB;
        $student_count=0;



        $context = context_course::instance($values->id);
        $users = get_enrolled_users($context);
       
   
        foreach ($users as $user) {
            $role = $DB->get_record_sql("SELECT roleid from {role_assignments} where contextid='$context->id' and userid=$user->id");
            $is_student = $DB->get_record_sql("SELECT * from {role} where id='$role->roleid' and shortname='student'");
            // $never_accessed_students=0;



            if ($is_student) {

                $student_count++;
                $lastaccess = $DB->get_field_sql("SELECT timeaccess FROM {user_lastaccess} WHERE courseid = ? AND userid = ?", array($values->id, $user->id));
            if (!$lastaccess) {
                $never_accessed_students++;
                }
            }
        }
       
        $percentage=0;
       
        if($student_count!=0){
        $percentage=($never_accessed_students/$student_count)*100;
        }
        if($student_count==0){
            if ($this->is_downloading()) {
                return 'No student enrolled';
            } else {
                return 'No student enrolled';
            }
        }
       

       


        if ($this->is_downloading()) {
            return (int)$percentage.'%';
        } else {
            return (int)$percentage.'%';
        }
    }
}
