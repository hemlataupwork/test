<?php
	require_once('../../config.php');
    require_login();

    global $USER, $DB, $CFG;

	$search_content = $_POST["in_name"]; 

    $_SESSION['filterr1']=$search_content;


	$students_information = $DB->get_records_sql("SELECT ue.*, u.firstname, u.alternatename, u.lastname, u.email, e.courseid, c.fullname,c.startdate, c.enddate FROM  {user} u
	    INNER JOIN {user_enrolments}  ue ON u.id =ue.userid 
	    INNER JOIN {enrol} e ON ue.enrolid =e.id 
	    INNER JOIN {course} c ON c.id = e.courseid 
        INNER JOIN {role_assignments} ra ON ra.userid = ue.userid
	    WHERE (u.id > 2  AND ra.roleid = 5 AND u.deleted =0 AND u.confirmed =1) AND (u.id LIKE '$search_content%' OR u.firstname LIKE  '$search_content%')");
	
    $row ='';
	foreach ($students_information as $student) 
    {
        $total_grade= $DB->get_record_sql("SELECT SUM(gg.finalgrade) AS total_grade
        FROM {grade_grades} gg
        JOIN {grade_items} gi ON gi.id = gg.itemid
        WHERE gg.userid = $student->userid
        AND gi.courseid = $student->courseid
        AND gi.itemtype IN ('mod', 'quiz')
        AND gi.hidden = 0");
        $current_date = strtotime(date('d-m-Y'));
        if ($current_date > $student->enddate) {
            $course_status = 'Past';
        } else {
            $course_status = 'Current';
        }

        $email2_field_id = $DB->get_field("user_info_field", 'id', ['shortname'=>'email2']);
        $student_email2 = $DB->get_record_sql("SELECT uiv.data FROM {user_info_data} uiv WHERE uiv.userid =$student->userid AND uiv.fieldid =$email2_field_id");
                                
        if ($student_email2) {
            $student_email2 = $student_email2->data;
        }
        else
        {
            $student_email2 = '';
        }

        $attendance_record = $DB->get_record_sql("SELECT attendance from {local_student_information} where userid=$student->userid and courseid=$student->courseid");

        if ($attendance_record) {
            $attendance = $attendance_record->attendance;
        }
        else {
            $attendance = 0;
        }
        
        $row .= "<tr>
        <td>
        <strong>
        $student->userid
    </strong></td>

        <td>
            <strong>$student->firstname</strong></td>
        <td>$student->alternatename </td>
        <td> <strong> $student->lastname</strong></td>
        <td class='flx'>$student->email <a href=$CFG->wwwroot/local/student_information/edit_form.php?id=$student->userid><i class='bi bi-pencil-square'></i></a></td>

        <td>$student_email2</td>
        <td class='cource_name' >$student->fullname</td>
        <td >";
        if($course_status == 'Current') {
            $row .="<p class='status pending'> $course_status
            </p>";
        } else {
            $row .="<p class='status cancelled'> $course_status
            </p>";
        }

        $row .= "

        
     
       
        </td>
        <td>" . round($total_grade->total_grade, 2) . "</td>
        <td>$attendance</td>
    </tr>";
    }

    $response =array();
    $response['row'] =$row;
	echo json_encode($response);
	exit();
?>