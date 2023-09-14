<?php


/**
 * Simple file test_custom.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
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

echo '<link rel="stylesheet" href="../css/style.css">';
$title = 'Hours per week online on moodle by each teacher
';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/data_analytics/data_ana3');
// $download = optional_param('download', '', PARAM_ALPHA);
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add("Data Analytics", new moodle_url('/local/data_analytics/index.php'));
$PAGE->navbar->add("Hours per week online on moodle by each teacher");

echo $OUTPUT->header();



echo '<form method="POST" class="forms">';
echo '<div class="form"><label for="startdate">Start date:</label><br>';
echo '<input type="date" id="startdate" name="startdate" value="'.$stdt.'" required></div>';
echo '<br>';
echo '<div><label for="enddate">End date:</label><br>';
echo ' <input type="date" id="enddate" name="enddate" value="'.$etdt.'" required></div>';
echo '<br>';
echo ' &nbsp; &nbsp;<input type="submit" value="Find" class="button">';
echo '</form>';
    

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $stdt>$etdt) {
    echo('<h6 style="color:red;">You entered invalid date.</h6>');

}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    redirect($CFG->wwwroot . '/local/data_analytics/data_ana3/table.php?startdate='.$stdt.'&enddate='.$etdt.'');
 }  
 echo"
 <style>
    section#region-main {
    background-image: linear-gradient(107deg,rgba(206, 255, 8, 0.048) 0%,rgba(255, 255, 255, 0.49) 35%);
}
 </style>
 
 ";

echo $OUTPUT->footer();