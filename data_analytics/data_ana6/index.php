<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php'); // Moodle configuration file
require "$CFG->libdir/tablelib.php";
require "class.php";
require_login(); // make sure the user is logged in
$homeurl = new moodle_url('/my');
if (user_has_role_assignment($USER->id,5)) {
    redirect($homeurl, "This feature is only available for site administrators.", 5);
}

if($_POST['startdate'] && $_POST['enddate']){
    $stdt=$_POST['startdate'];
    $etdt=$_POST['enddate'];
}else{
   $stdt='';
    $etdt='';
}

$title = 'How many logins to moodle per week, per teacher
';
echo '<link rel="stylesheet" href="../css/style.css">';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/data_analytics/data_ana6');
$PAGE->set_pagelayout('standard');
// $download = optional_param('download', '', PARAM_ALPHA);
$PAGE->navbar->add("Data Analytics", new moodle_url('/local/data_analytics/index.php'));
$PAGE->navbar->add("How many logins to moodle per week, per teacher");

echo $OUTPUT->header();



echo '<form method="POST" class="forms">';
echo '<div class="form"><label for="startdate">Start date:</label><br>';
echo '<input type="date" id="startdate" name="startdate" value="'.$stdt.'" required></div>';
echo '<br>';
echo '<div class="form" > <label for="enddate">End date:</label><br>';
echo ' <input type="date" id="enddate" name="enddate" value="'.$etdt.'" required></div>';
echo '<br>';
echo ' &nbsp; &nbsp;<input type="submit" value="Find" class="button">';
echo '</form>';
 
global $CFG;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $stdt>$etdt) {
    echo('<h6 style="color:red;">You entered invalid date.</h6>');

}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    redirect($CFG->wwwroot . '/local/data_analytics/data_ana6/table.php?startdate='.$stdt.'&enddate='.$etdt.'');
}
echo"
<style>
    section#region-main {
    background-image:#fdfff4;
}

input#startdate ,input#enddate{
    border-radius: 20px;
    padding: 6px 26px;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
 
    background: #ffffff;
    border: none;
    outline: none;
}
.form {
    display: flex;
    gap: 10px;
    justify-content: center;
    padding: 7px 15px;
    border-radius: 20px;
    border: 1px solid #d7d7d7;
    align-items: baseline;
    /* border-bottom: 1px solid #cdcdcd; */
}
</style>
";

echo $OUTPUT->footer();