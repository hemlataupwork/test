<?php
require_once('../../config.php');
require_once($CFG->dirroot . '/completion/classes/progress.php');
require_login();
$homeurl = new moodle_url('/my');
require_login();
if (!is_siteadmin()) {
    redirect($homeurl, "This feature is only available for site administrators.", 5);
}


global $USER, $DB, $CFG;
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Student Information');

$PAGE->set_url(new moodle_url('/local/student_information/index.php'));
$context = context_system::instance();
$PAGE->set_context($context);

echo $OUTPUT->header();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta set="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Inormation</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <style>
        table thead tr th{
            white-space: pre;
            background: #0f6cbf;
        }
        .search-label{
            font-weight: 600;
        }
        #search_input{
            border-radius: 3px;
            width: 250px;
            outline: none;
            font-size: 14px;
            background: none;
            padding: 8px;
            border: 2px solid #ddd;
            transition: border-color 0.3s ease;
        }
        .flx{
            display:flex;
            justify-content: space-between;
        }
        .flx a i{
            font-size: 13px !important;
            border: 1px solid #cfcfcf;
            padding: 5px;
            background: #dee2e6;
            border-radius: 4px;
            color: #0000007a;
            margin-left: 15px;
        }
        .custom-btn{
            background: #0f6cbf;
            border: 1px solid #0f6cbf !important;
        }
        #download_type{
            border: 2px solid #ddd;
            color: #000000b0;
        }
        .breadcrumb-item{
            font-weight: 500;
        }
        .table-bordered td{
            color: #000000b0;
        }
        .page-header-headings h1{
            font-size: 25px;
            font-weight: 500;
            margin-bottom:0px;
        }
        .overflow{
            overflow-y:hidden;
            overflow-x:scroll;
        }
        .overflow::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        .overflow::-webkit-scrollbar
        {
            width: 6px;
            height:5px;
            background-color: #F5F5F5;
        }

        .overflow::-webkit-scrollbar-thumb
        {
            background-color: #dee2e6;
        }
    </style>
</head>

<body>
    <?php
    $students_information = $DB->get_records_sql("SELECT ue.*, u.firstname, u.alternatename, u.lastname, u.email, e.courseid, c.fullname,c.startdate, c.enddate FROM  {user} u
            INNER JOIN {user_enrolments}  ue ON u.id =ue.userid 
            INNER JOIN {enrol} e ON ue.enrolid =e.id 
            INNER JOIN {course} c ON c.id = e.courseid 
            INNER JOIN {role_assignments} ra ON ra.userid = ue.userid 
            WHERE u.id > 2  AND u.deleted =0 AND u.confirmed =1 AND ra.roleid = 5 ORDER BY u.id ASC;");
    // var_dump($students_information);
    // die;
    #Moodle Pagination 
    $perpage = optional_param('perpage', 10, PARAM_INT);
    $page = optional_param('page', 0, PARAM_INT);
    $count = count($students_information);
    $start = $page * $perpage;
    if ($start > $count) {
        $page = 0;
        $start = 0;
    }
    $students_information = array_slice($students_information, $start, $perpage, true);
    $totalcount = $count;
    $baseurl = new moodle_url('/local/student_information/index.php');
    ?>
    <div class="container" style="max-width:100%;">
        <div id="">
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="row justify-content-between mb-3">
                        <div class="col-md-3">
                            <label for="search_input" class="search-label"><?= get_string('search', 'local_student_information'); ?></label><br>
                            <input type="text" id="search_input" placeholder="<?= get_string('searchstudent', 'local_student_information'); ?>" onkeyup="get_student_info(this.value)">
                        </div>
                    </div>
                    <div class="overflow">
                        <table class="table table-hover table-bordered" id="student_information">
                            <thead>
                                <tr class="bg-secondary text-white">
                                    <th><?= get_string('moodleid', 'local_student_information'); ?></th>
                                    <th><?= get_string('fname', 'local_student_information'); ?></th>
                                    <th><?= get_string('oname', 'local_student_information'); ?></th>
                                    <th><?= get_string('lname', 'local_student_information'); ?></th>
                                    <th><?= get_string('email', 'local_student_information'); ?></th>
                                    <th><?= get_string('email2', 'local_student_information'); ?></th>
                                    <th><?= get_string('coursename', 'local_student_information'); ?></th>
                                    <th><?= get_string('coursestatus', 'local_student_information'); ?></th>
                                    <th><?= get_string('coursegrade', 'local_student_information'); ?></th>
                                    <th><?= get_string('attendance', 'local_student_information'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="stu_info">
                                <?php
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
                                    
                                    //start
                                    $attendance_record = $DB->get_record_sql("SELECT attendance from {local_student_information} where userid=$student->userid and courseid=$student->courseid");

                                    if ($attendance_record) {
                                        $attendance = $attendance_record->attendance;
                                    }
                                    else {
                                        $attendance = 0;
                                    }
                                    echo "<tr>
                                            <td>$student->userid</td>
                                            <td>$student->firstname</td>
                                            <td>$student->alternatename </td>
                                            <td>$student->lastname</td>
                                            <td class='flx'>$student->email <a href=$CFG->wwwroot/local/student_information/edit_form.php?id=$student->userid><i style='font-size:24px'  class='fas'>&#xf304;</i></a></td>

                                            <td>$student_email2</td>
                                            <td>$student->fullname</td>
                                            <td>$course_status</td>
                                            <td>" . round($total_grade->total_grade, 2) . "</td>
                                            <td>$attendance</td>
                                        </tr>";
                                    //end
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="my-2">
                <?php
                #Moodle Pagination Number Show
                echo $OUTPUT->paging_bar($count, $page, $perpage, $baseurl);
                ?>
            </div>
            <div class="row">
                <div class="col-12 text-end d-flex justify-content-end float">
                    <form action="<?php echo $CFG->wwwroot . '/local/student_information/htmltopdf.php'; ?>" method="post">
                        <input type="hidden" name="perpage" value="<?php echo $perpage; ?>">
                        <input type="hidden" name="page" value="<?php echo $page; ?>">
                        <select name="download_type" id="download_type" style="min-width: 100px; height: 31px;">
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                        <button type="submit" class="btn btn-info py-1 border custom-btn" style="margin-top: -5px;"><?= get_string('download', 'local_student_information'); ?></button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- ---------------------------- JavaScipt CDN Block ------------------------------------------- -->
    <!-- <script src="<?php echo $CFG->wwwroot . '/local/student_information/student_ajax.php'; ?>"></script> -->
    <!-- ---------------------------- JavaScipt CDN Block ------------------------------------------- -->
</body>

</html>
<?php
echo $OUTPUT->footer();
?>

<script>
    function get_student_info(in_name) {
        if (in_name) 
        {
            $.ajax({
                url: "<?php echo $CFG->wwwroot . '/local/student_information/get_student_info.php'; ?>",
                method: 'POST',
                data: {
                    in_name: in_name
                },
                dataType: 'json',
                success: function(responsedata) {
                    // console.log(responsedata);
                    stu_info.innerHTML = responsedata.row;
                    document.getElementsByClassName('pagination-centered')[0].style.display='none';
                    // var pagination_centered = 
                }
            });
        } 
        else 
        {
            window.location.reload();
            <?php
                $_SESSION['filterr1'] = 'error';
            ?>
        }
    }
</script>