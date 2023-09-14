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
        $headers = array('S no.', 'Course name', 'Class name', 'Assignment name', 'Percentage of students completing an assessment by the due date');
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
        $student_count = 0;
        $submit = 0;

        $context = context_course::instance($values->course);
        $users = get_enrolled_users($context);
        foreach ($users as $user) {
            $role = $DB->get_record_sql("SELECT roleid from {role_assignments} where contextid='$context->id' and userid=$user->id");
            $is_student = $DB->get_record_sql("SELECT * from {role} where id='$role->roleid' and shortname='student'");

            if ($is_student) {
                $student_count++;
                $is_submitted = $DB->get_record_sql("SELECT * from {assign_submission} where assignment='$values->id' and userid=$user->id and status = 'submitted'");
                
                if ($is_submitted) {
                    if ($is_submitted->timecreated <= $values->duedate) {
                        $submit++;
                    }
                }
            }
        }
       
        $completion_percentage = ($submit / $student_count) * 100;






        if ($this->is_downloading()) {
            return (int)$completion_percentage . '%';
        } else {
            return (int)$completion_percentage . '%';
        }
    }
}
