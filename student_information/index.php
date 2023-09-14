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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <style>
    

        * {
            margin: 0;
            padding: 0;
        
            box-sizing: border-box;
            font-family: sans-serif;
        }
        
       
        
        main.table {
           margin: 10px;
            height: 90vh;
            background-color: #fff5;
        width: 99%;
            backdrop-filter: blur(7px);
            box-shadow: 0 .4rem .8rem #0005;
            border-radius: .8rem;
        
            overflow: hidden;
        }
        
        .table__header {
            width: 100%;
            height: 10%;
            background-color: #fff4;
            padding: .8rem 1rem;
        
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table__header .input-group {
            width: 35%;
            height: 100%;
            background-color: #fff5;
            padding: 0 .8rem;
            border-radius: 2rem;
        
            display: flex;
            justify-content: center;
            align-items: center;
        
            transition: .2s;
        }
        
        .table__header .input-group:hover {
            width: 45%;
            background-color: #fff8;
            box-shadow: 0 .1rem .4rem #0002;
        }
        
        .table__header .input-group img {
            width: 1.2rem;
            height: 1.2rem;
        }
        
        .table__header .input-group input {
            width: 100%;
            padding: 0 .5rem 0 .3rem;
            background-color: transparent;
            border: none;
            outline: none;
        }
        
        .table__body {
            width: 95%;
            max-height: calc(89% - 1.6rem);
            background-color: #fffb;
        
            margin: .8rem auto;
            border-radius: .6rem;
        
            overflow: auto;
            overflow: overlay;
        }
        
        .table__body::-webkit-scrollbar{
            width: 0.5rem;
            height: 0.5rem;
        }
        i.fas {
            color:#26919a;
    /* font-size: 10px; */
    font-size: 11px !important;
    margin-left: 18px;
}
        .table__body::-webkit-scrollbar-thumb{
            border-radius: .5rem;
            background-color: #0004;
            visibility: hidden;
        }
        
        .table__body:hover::-webkit-scrollbar-thumb{ 
            visibility: visible;
        }
        
        table {
            width: 100%;
        }
        
        td img {
            width: 36px;
            height: 36px;
            margin-right: .5rem;
            border-radius: 50%;
        
            vertical-align: middle;
        }
        
        table, th, td {
            border-collapse: collapse;
            padding: 1rem;
            text-align: left;
        }
        
        thead th {
            position: sticky;
            top: 0;
            left: 0;
            background-color: #8cd3ff;
            cursor: pointer;
            text-transform: capitalize;
        }
        
        tbody tr:nth-child(even) {
            background-color: #0000000b;
        }
        
        tbody tr {
            --delay: .1s;
            transition: .5s ease-in-out var(--delay), background-color 0s;
        }
        
        tbody tr.hide {
            opacity: 0;
            transform: translateX(100%);
        }
        
        tbody tr:hover {
            background-color: #fff6 !important;
        }
        
        tbody tr td,
        tbody tr td p,
        tbody tr td img {
            transition: .2s ease-in-out;
        }
        
        tbody tr.hide td,
        tbody tr.hide td p {
            padding: 0;
            font: 0 / 0 sans-serif;
            transition: .2s ease-in-out .5s;
        }
        
        tbody tr.hide td img {
            width: 0;
            height: 0;
            transition: .2s ease-in-out .5s;
        }
        
        .status {
            padding: .4rem 0;
            border-radius: 2rem;
            text-align: center;
        }

        .status.delivered {
            background-color: #86e49d;
            color: #006b21;
        }
        
        .status.cancelled {
            background-color: #d893a3;
            color: #b30021;
        }
        
        .status.pending {
            background-color: #ebc474;
        }
        
        .status.shipped {
            background-color: #6fcaea;
        }
        
        
        @media (max-width: 1000px) {
            td:not(:first-of-type) {
                min-width: 12.1rem;
            }
        }
        
        thead th span.icon-arrow {
            display: inline-block;
            width: 1.3rem;
            height: 1.3rem;
            border-radius: 50%;
            border: 1.4px solid transparent;
            
            text-align: center;
            font-size: 1rem;
            
            margin-left: .5rem;
            transition: .2s ease-in-out;
        }
        
        thead th:hover span.icon-arrow{
            border: 1.4px solid #6c00bd;
        }
        
        thead th:hover {
            color: #ffff;
        }
        
        thead th.active span.icon-arrow{
            background-color: #6c00bd;
            color: #fff;
        }
        
        thead th.asc span.icon-arrow{
            transform: rotate(180deg);
        }
        
        thead th.active,tbody td.active {
            color: #6c00bd;
        }
        
        .export__file {
            position: relative;
        }
        
        .export__file .export__file-btn {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            background: #fff6 url(images/export.png) center / 80% no-repeat;
            border-radius: 50%;
            transition: .2s ease-in-out;
        }


        
        .export__file .export__file-btn:hover { 
            background-color: #fff;
            transform: scale(1.15);
            cursor: pointer;
        }
        
        .export__file input {
            display: none;
        }
        
        .export__file .export__file-options {
            position: absolute;
            right: 0;
            
            width: 12rem;
            border-radius: .5rem;
            overflow: hidden;
            text-align: center;
        
            opacity: 0;
            transform: scale(.8);
            transform-origin: top right;
            
            box-shadow: 0 .2rem .5rem #0004;
            
            transition: .2s;
        }
        
        .export__file input:checked + .export__file-options {
            opacity: 1;
            transform: scale(1);
            z-index: 100;
        }
        
        .export__file .export__file-options label{
            display: block;
            width: 100%;
            padding: .6rem 0;
            background-color: #f2f2f2;
            
            display: flex;
            justify-content: space-around;
            align-items: center;
        
            transition: .2s ease-in-out;
        }
        
        .export__file .export__file-options label:first-of-type{
            padding: 1rem 0;
            background-color: #86e49d !important;
        }
        
        .export__file .export__file-options label:hover{
            transform: scale(1.05);
            background-color: #fff;
            cursor: pointer;
        }
        
        .export__file .export__file-options img{
            width: 2rem;
            height: auto;
        }
  
        </style>
    <style>
        table thead tr th{
            white-space: pre;
           
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
            
            transition: border-color 0.3s ease;
        }
        .flx{
            display:flex;
            justify-content: space-between;
        }
       

.flx a .bi {
    font-size: 18px !important;
    padding: 5px;
    border-radius: 4px;
    color:#26919a;
    margin-left: 15px;
}
.cource_name{
    color:#005696;
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
        .table__header .input-group {
    width: 35%;
    height: 100%;
    background-color: #fff5;
    padding: 0 0.8rem;
    border-radius: 2rem;
    border: 1px solid #cfcfcf;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: .2s;
    flex-direction: column;
  
}
span.email {
    display: flex;
    gap: 14px;
    align-items: center;
    justify-content: center;
}
.Information{
    color: #3c9cd7;
}
section#region-main {
    background-image: linear-gradient(107deg,rgba(206, 255, 8, 0.048) 0%,rgba(255, 255, 255, 0.49) 35%);
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
                    <div class="overflow">
                        <main class="table">
                            <section class="table__header">
                                <h1>Student's <span class="Information" >Information</span></h1>
                                <!-- <div class="col-md-3">
                                    <label for="search_input" class="search-label"><?= get_string('search', 'local_student_information'); ?></label><br>
                                    <input type="text" id="search_input" placeholder="<?= get_string('searchstudent', 'local_student_information'); ?>" onkeyup="get_student_info(this.value)">
                                </div> -->
                                <div class="input-group">
                                    <input type="text" id="search_input" placeholder="Search Name or id" onkeyup="get_student_info(this.value)">
                                    <img src="images/search.png" alt="">
                                </div>
                                <div class="export__file">

                                </div>
                            </section>
                            <section class="table__body">
                                <table>
                                    <thead>
                                        <tr>
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
                                    $html .= "<tr>
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
                                                $html .="<p class='status pending'> $course_status
                                                </p>";
                                            } else {
                                                $html .="<p class='status cancelled'> $course_status
                                                </p>";
                                            }
                                    
                                    $html .= "</td>
                                    <td>" . round($total_grade->total_grade, 2) . "</td>
                                    <td>$attendance</td>
                                </tr>";
                                    //end
                                echo ($html);    
                                }
                                ?>
                                    </tbody>
                                </table>
                            </section>
                        </main>
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