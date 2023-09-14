<?php

namespace local_student_information;

defined('MOODLE_INTERNAL') || die();

class observers
{
    public static function attendance1(\core\event\course_viewed $event)
    {
        // Add your custom code here
        $event_data = $event->get_data();

        global $DB;

        $date = date('d-m-Y');
        $curr_date = strtotime($date);
        
        
        $userid = $event_data['userid'];
        $courseid = $event_data['courseid'];
        
        $record = $DB->get_record_sql("SELECT * FROM {local_student_information} WHERE userid=$userid and courseid=$courseid");
       
        if ($record) {
            if ($record->dateid != $curr_date) {
                $attendance = $record->attendance;
                $attendance++;
            
                $DB->execute("UPDATE {local_student_information} SET dateid='$curr_date' AND attendance=$attendance WHERE userid=$userid AND courseid=$courseid");
            }
        } else {
            $data = new \stdClass();
            $data->userid = $userid;
            $data->courseid = $courseid;            
            $data->dateid = $curr_date;
            $data->attendance = '1';
        

            $DB->insert_record('local_student_information', $data);
        }
    }
}
