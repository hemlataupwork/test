<?php
require_once('../../config.php');
global $USER, $DB, $CFG;
require_once($CFG->dirroot . '/completion/classes/progress.php');
// require 'vendor/autoload.php';
include "simple_html_dom.php";
require_once __DIR__ . '/vendor/autoload.php';

require_login();
$perpage = $_POST['perpage'];
$download_type = $_POST['download_type'];
$search_content = $_SESSION['filterr1'];

if ($perpage === 'all') {
	$students_information =  $DB->get_records_sql("SELECT ue.*, u.firstname, u.alternatename, u.lastname, u.email, e.courseid, c.fullname,c.startdate, c.enddate  FROM  {user} u
		INNER JOIN {user_enrolments}  ue ON u.id =ue.userid 
		INNER JOIN {enrol} e ON ue.enrolid =e.id 
		INNER JOIN {course} c ON c.id = e.courseid 
		WHERE u.id > 2  AND u.deleted =0 AND u.confirmed =1 AND e.roleid = 5 ORDER BY u.id ASC;");
} else {
	$page = $_POST['page'];
	$offset = $page * $perpage;
	$limit = $perpage;

	$students_information = $DB->get_records_sql("SELECT ue.*, u.firstname, u.alternatename, u.lastname, u.email, e.courseid, c.fullname,c.startdate, c.enddate  FROM  {user} u
		INNER JOIN {user_enrolments}  ue ON u.id =ue.userid 
		INNER JOIN {enrol} e ON ue.enrolid =e.id 
		INNER JOIN {course} c ON c.id = e.courseid 
		WHERE u.id > 2  AND u.deleted =0 AND u.confirmed =1 AND e.roleid = 5 ORDER BY u.id ASC ;");
}
if ($search_content != 'error') {
	$students_information = $DB->get_records_sql("SELECT ue.*, u.firstname, u.alternatename, u.lastname, u.email, e.courseid, c.fullname,c.startdate, c.enddate  FROM  {user} u
	    INNER JOIN {user_enrolments}  ue ON u.id =ue.userid 
	    INNER JOIN {enrol} e ON ue.enrolid =e.id 
	    INNER JOIN {course} c ON c.id = e.courseid 
	    WHERE u.id > 2  AND u.deleted =0 AND u.confirmed =1 AND e.roleid = 5 AND u.id LIKE '$search_content%' OR u.firstname LIKE  '$search_content%'");
}



$html_table = '<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
			<style>
			table {
				border-collapse: collapse;
				border: 1px solid black; /* Add this line to set a border */
				width: 100%;
			}
			
			th, td {
				border: 1px solid black;
				padding: 5px;
			}
			
			tr:nth-child(even) {
				background-color: #f2f2f2;
				border: 1px solid black;
			}
		</style>
		
		</head>
		<body>
		<table class="table table-hover table-bordered" id="user_certificate_report">
			<thead>
				<tr class="" style="background: #3ac999;">
					<th class="py-" style="color: #fff; "> ' . get_string('moodleid', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('fname', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('oname', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('lname', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('email', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('email2', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('coursename', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('coursestatus', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('coursegrade', 'local_student_information') . '</th>
					<th class="py-" style="color: #fff; "> ' . get_string('attendance', 'local_student_information') . '</th>
				</tr>
			</thead>
			<tbody>';
foreach ($students_information as $student) {
	$total_grade = $DB->get_record_sql("SELECT SUM(gg.finalgrade) AS total_grade
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
	$html_table .= "<tr>
		<td>$student->userid</td>
		<td>$student->firstname</td>
		<td>$student->alternatename</td>
		<td>$student->lastname</td>
		<td>$student->email</td>
		<td>$student_email2</td>
		<td>$student->fullname</td>
		<td>$course_status</td>
		<td>" . round($total_grade->total_grade, 2) . "</td>
		<td>$attendance</td>
	</tr>";
}

$html_table .= '</tbody>
	</table>
	</body>
	</html>';
// echo $html;
// Create a new instance of mpdf
// Define a page size/format by array - page will be 190mm wide x 236mm height
if ($download_type === "pdf") {
	$mpdf = new \Mpdf\Mpdf();

	$mpdf->autoScriptToLang = true;
	$mpdf->autoLangToFont = true;
	$mpdf->default_font = 'dejavusans';

	mb_internal_encoding('UTF-8');
	$mpdf->WriteHTML($html_table);
	$mpdf->Output('Student Information.pdf', 'D');
}

if ($download_type === "csv") {
	header('Content-type: application/ms-excel');
	header("Content-Disposition: attachment; filename=Student Information.csv");
	$html = str_get_html($html_table);
	$fp = fopen("php://output", "w");
	fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
	foreach ($html->find('tr') as $element) {
		$th = array();
		foreach ($element->find('th') as $row) {
			$th[] = $row->plaintext;
		}

		$td = array();
		foreach ($element->find('td') as $row) {
			$td[] = $row->plaintext;
		}
		!empty($th) ? fputcsv($fp, $th) : fputcsv($fp, $td);
	}
	fclose($fp);
}
