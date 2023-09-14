<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php'); // Moodle configuration file

require_login(); // make sure the user is logged in
$homeurl = new moodle_url('/my');
if (user_has_role_assignment($USER->id, 5)) {
    redirect($homeurl, "This feature is only available for site administrators.", 5);
}
// Get the global $DB object
global $DB;

// Replace <category_id> with the ID of the category you want to retrieve courses for.
$category_id = $_GET['category_id'];






$sql = "SELECT c.fullname,c.id, COUNT(DISTINCT ue.userid) as enrolled_count
        FROM {course} c
        JOIN {course_categories} cc ON c.category = cc.id
        JOIN {enrol} e ON c.id = e.courseid
        JOIN {user_enrolments} ue ON e.id = ue.enrolid
        JOIN {role_assignments} ra ON ue.userid = ra.userid
        WHERE cc.id = :category_id
        AND ra.roleid = 5
        GROUP BY c.fullname";

$params = array('category_id' => $category_id);

$courses = $DB->get_records_sql($sql, $params);

$course_info = array();

foreach ($courses as $course) {
     $student_count=0;
      $context = context_course::instance($course->id);
      $users = get_enrolled_users($context);
      
             foreach ($users as $user) {
            $role = $DB->get_record_sql("SELECT roleid from {role_assignments} where contextid='$context->id' and userid=$user->id");
            $is_student = $DB->get_record_sql("SELECT * from {role} where id='$role->roleid' and shortname='student'");
            if ($is_student) {
                $student_count++;
          
            }
        }
    
    $course_info[] = array(
        'name' => $course->fullname,
        'count' => $student_count
    );
}

// Moodle header
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Average Enrolled Student numbers per Moodle class');
$PAGE->set_heading('Average Enrolled Student numbers per Moodle class');
// Output the page
$PAGE->navbar->add("Data Analytics", new moodle_url('/local/data_analytics/index.php'));
$PAGE->navbar->add("Average Enrolled Student numbers per Moodle class", new moodle_url('/local/data_analytics/data_ana1/'));
$category_id = $_GET['category_id'];
$a_parent = $DB->get_record_sql("SELECT parent FROM {course_categories} WHERE id=$category_id");
$cat_store = new SplStack();

//Moodle navbar
if ($a_parent->parent != 0) {
    while ($a_parent->parent != 0) {

        $cat_store[] = $a_parent->parent;
        $a_parent = $DB->get_record_sql("SELECT parent FROM {course_categories} WHERE id=$a_parent->parent");
    }
} 

foreach ($cat_store as $elem) {
    $cat_name = $DB->get_record_sql("SELECT name FROM {course_categories} WHERE id=$elem");
    $PAGE->navbar->add("$cat_name->name",new moodle_url('/local/data_analytics/data_ana1/'));

}
$cat_name = $DB->get_record_sql("SELECT name FROM {course_categories} WHERE id=$category_id");
$PAGE->navbar->add("$cat_name->name");
//end navbar
echo "
<style>
.chartWrapper {
	--scrollbarBG: ;
	--thumbBG: #bbbbbb;
}

.chartWrapper::-webkit-scrollbar {
	width: 5px;
	height: 5px;
}

.chartWrapper {
	scrollbar-width: thin;
	scrollbar-color: var(--thumbBG) var(--scrollbarBG);

}

.chartWrapper::-webkit-scrollbar-track {
	background: var(--scrollbarBG);
}

.chartWrapper::-webkit-scrollbar-thumb {
	background-color: var(--thumbBG);
	border-radius: 6px;
	border: 0px solid var(--scrollbarBG);
}
#myChart{
    max-width: 100% !important;
}
.chartWrapper {
	position:relative;
        overflow-x: scroll;
}
section#region-main {
    background-image: linear-gradient(107deg,rgba(206, 255, 8, 0.048) 0%,rgba(255, 255, 255, 0.49) 35%);
}
.chartWrapper>canvas {
	position: absolute;
	left: 0;
	top: 0;
	pointer-events: none;
}
#myChart {
    max-width: 100% !important;
    padding: 15px;
}

.chartAreaWrapper {
    box-shadow: rgba(113, 152, 182, 0.24) 0px 3px 8px;
    background-color: white;
    margin: 23px;
    border-radius: 45px;
}
/* .myChart > title {
  font-weight: bold;
} */

</style>
";
echo $OUTPUT->header();
?>
<div class="chartWrapper">
    <div class="chartAreaWrapper">
<canvas id="myChart" style="max-width:80%; "></canvas>
</div>
    <canvas id="myChartAxis" height="300" width="0"></canvas>
</div>
<link rel="stylesheet" href="../css/style.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
var xValues = <?php echo json_encode(array_column($course_info, 'name')); ?>;
var yValues = <?php echo json_encode(array_column($course_info, 'count')); ?>;
var barColors = <?php echo json_encode(array_map(function () {
                        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                    }, range(0, count($course_info) - 1))); ?>;

new Chart("myChart", {
    type: "bar",
    data: {
        labels: xValues,
        datasets: [{
            backgroundColor: barColors,
            data: yValues,
            borderRadius:10,
        }]
    },
    options: {
        legend: {
            display: false
        },
        title: {
            display: true,
            
            text: "Enrollment by Course"
        },
        scales: {
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Enrolled Students'
                },
                ticks: {
                    beginAtZero: true,
                }
            }],
            xAxes: [{
                ticks: {
                    autoSkip: false,
                    callback: function(value) {
                        return value.substr(0, 10);//truncate
                    },
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Courses'
                },
                barThickness: 35, // set bar thickness to 40 pixels
            }]
        }
    }
});

</script>

<?php
// Moodle footer
echo $OUTPUT->footer();
?>