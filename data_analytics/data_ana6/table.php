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


    // Get the start date and end date from the form
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];
    // Use the dates to filter the results
    // ...
 
    session_start(); // Start the session
$_SESSION['startdate'] = $startdate;

$_SESSION['enddate'] = $enddate;

$table = new test_table('uniqueid');
$table->is_downloading($download, 'How many logins to moodle per week, per teacher
', 'testing123');
if (!$table->is_downloading()) {
    // Only print headers if not asked to download data.
    // Print the page header.
    $PAGE->set_title('How many logins to moodle per week, per teacher
');
    $PAGE->set_heading('How many logins to moodle per week, per teacher
');
    $PAGE->navbar->add('How many logins to moodle per week, per teacher
', new moodle_url('local/data_analytics/data_ana6/index.php'));
    $previewnode = $PAGE->navigation->add('How many logins to moodle per week, per teacher
', new moodle_url('/local/data_analytics/data_ana6/table.php'), navigation_node::TYPE_CONTAINER);
    // echo $OUTPUT->header();
    echo "<h5 class='date-heading'><p  class=' text-one mb-2'><b class='date-text' >Start Date :</b> ".$startdate."<br></p> <p class=' text-two mb-2'><b class='date-text' >End Date :</b>".$enddate."</p></h5>";
    echo "<a href='".$CFG->wwwroot."/local/data_analytics/data_ana6/index.php'><input type='button' value='Select Another Date' class='button'></a>";
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
$from = '{user} as u';
$where = "u.id in ($a_array2)";
$table->sortable(true, 'num');
$table->set_sql( $fields, $from,$where); 
$table->define_baseurl("$CFG->wwwroot/local/data_analytics/data_ana6/table.php?startdate=".$startdate."&enddate=".$enddate."");


$table->out(10, true);
echo '
<style>
    .resettable + .pagination {


    display: none;
}
    section#region-main {
    background-image: linear-gradient(107deg,rgba(206, 255, 8, 0.048) 0%,rgba(255, 255, 255, 0.49) 35%);
}
.text-one {
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    background: #8cd3ff75;
   
}
.date-heading .mb-2, .my-2 {
    margin-bottom: 0.5rem!important;
    
  
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    padding: 12px;
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
tbody {
    font-weight: bold;
}
    .generaltable thead th, .generaltable thead td {
    background: #8cd3ff;
   
    /* padding: 18px; */
}
strong.date-text {
    color: #2a80b9;
    font-weight: 400;
}
.generaltable th, .generaltable td {
    /* padding: 14px; */
    vertical-align: top;
    padding: 15px 11px 12px 27px;
    border-top: 1px solid #dee2e6;
}
h5.date-heading {
    display: flex;
    font-size: 16px;
}
.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    margin-top: 20px;
    border-radius: 0.25rem;
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
    
}

    
echo $OUTPUT->footer();