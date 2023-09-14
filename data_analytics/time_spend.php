<?php

require_once('../../config.php');
global $DB, $USER;

$date = date('d-m-Y');
$curr_date = strtotime($date);



$record = $DB->get_record_sql("SELECT * FROM {teacher_timespend} WHERE userid=$USER->id AND date=$curr_date");
// die('success');
if ($record) {
    $duration_time = $record->timespend;
    $duration_time += 2;
    $DB->execute("UPDATE {teacher_timespend} SET timespend='$duration_time' WHERE userid=$USER->id AND date=$curr_date");
} else{
    $data = new stdClass();
   $data->userid = $USER->id;
   $data->date = $curr_date;
   $DB->insert_record('teacher_timespend', $data);
}