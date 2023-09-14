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
 // Get the start date and end date from the form
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];
    // Use the dates to filter the results
    // ...

    session_start(); // Start the session
$_SESSION['startdate'] = $startdate;

$_SESSION['enddate'] = $enddate;

$table = new test_table('uniqueid');
$table->is_downloading($download, 'Hours per week online on Moodle by each teacher
', 'testing123');
if (!$table->is_downloading()) {
    // Only print headers if not asked to download data.
    // Print the page header.
    $PAGE->set_title('Hours per week online on Moodle by each teacher
');
    $PAGE->set_heading('Hours per week online on Moodle by each teacher
');
    $PAGE->navbar->add('Hours per week online on Moodle by each teacher
', new moodle_url('local/data_analytics/data_ana3/index.php'));
    $previewnode = $PAGE->navigation->add('Hours per week online on Moodle by each teacher
', new moodle_url('/local/data_analytics/data_ana3/index.php'), navigation_node::TYPE_CONTAINER);
    // echo $OUTPUT->header();
     echo "<h5 class='date-heading'><p class='text-one  mb-2'><strong class='date-text'>Start Date :</strong>  ".$startdate."<br></p> <p class='text-two mb-2'><strong class='date-text' >End Date :</strong>  ".$enddate."</p></h5>";
    echo "<a href='".$CFG->wwwroot."/local/data_analytics/data_ana3/index.php'><input type='button' value='Select Another Date' class='button'></a>";
}

global $DB;
global $SNO;
$SNO=0;
$catid= $_GET['category_id'];


$teachers=$DB->get_records_sql("SELECT DISTINCT userid FROM {role_assignments} where roleid=3");
$a_array= array();
foreach($teachers as $teacher){
    $a_array[] = $teacher->userid;
}
$a_array2=implode(',',$a_array);

$DB->execute('SET @row_number = 0', array());
$fields = '*,(@row_number:=@row_number + 1) as num';
$from = '{user} as u  ';
$where = "u.id in ($a_array2)";
$table->sortable(true, 'num');
$table->set_sql( $fields, $from,$where); 
$table->define_baseurl("$CFG->wwwroot/local/data_analytics/data_ana3/table.php?startdate=".$startdate."&enddate=".$enddate."");

$table->out(10, true);

echo '
<style>
strong.date-text {
    color: #2a80b9;
    font-weight: 400;
}
    .resettable + .pagination {
    display: none;
}
    section#region-main {
    background-image: linear-gradient(107deg,rgba(206, 255, 8, 0.048) 0%,rgba(255, 255, 255, 0.49) 35%);
}

.resettable.mdl-right {
    position: relative;
}
.resettable a {
    /* background: red; */
    position: absolute;
    margin-bottom: 35px;
    top: -29px;
    right: 0;
}
.text-one {
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    background: #8cd3ff75;
   
}
.text-two{
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;

}
.no-overflow {
    overflow: auto;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    border-radius: 26px;
    margin-top: 13px;
    /* padding: 10px; */
}
tbody {
    font-weight: bold;
}
    .generaltable thead th, .generaltable thead td {
    background: #8cd3ff;
   
    /* padding: 18px; */
}
.generaltable th, .generaltable td {
    /* padding: 14px; */
    vertical-align: top;
    padding: 15px 11px 12px 27px;
    border-top: 1px solid #dee2e6;
}
.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    margin-top: 20px;
    border-radius: 0.25rem;
}
h5.date-heading {
    display: flex;
    font-size: 16px;
}
.date-heading .mb-2, .my-2 {
    margin-bottom: 0.5rem!important;
    
  
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    padding: 12px;
}
</style>
<style>
.commands
{
    display:none !important;
} 
.header a{
    pointer-events: none !important;
}
</style>
';
if (!$table->is_downloading()) {
   echo $OUTPUT->footer(); 
}

