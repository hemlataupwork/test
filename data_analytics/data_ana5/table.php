<?php

/**
 * Simple file test_custom.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php'); // Moodle configuration file
require "$CFG->libdir/tablelib.php";
require "class.php";
require_login(); // make sure the user is logged in
$homeurl = new moodle_url('/my');
if (user_has_role_assignment($USER->id, 5)) {
    redirect($homeurl, "This feature is only available for site administrators.", 5);
}

$title = 'Percentage of students receiving less than 50% for an assessment (by assessment)
';
echo '<link rel="stylesheet" href="../css/style.css">';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/data_analytics/data_ana5');
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add("Data Analytics", new moodle_url('/local/data_analytics/index.php'));
// $download = optional_param('download', '', PARAM_ALPHA);
$table = new test_table('uniqueid');
$table->is_downloading($download, 'Percentage of students receiving less than 50% for an assessment (by assessment)
', 'testing123');
if (!$table->is_downloading()) {
    // Only print headers if not asked to download data.
    // Print the page header.
    $PAGE->set_title('Percentage of students receiving less than 50% for an assessment (by assessment)
');
    $PAGE->set_heading('Percentage of students receiving less than 50% for an assessment (by assessment)
');
    $PAGE->navbar->add('Percentage of students receiving less than 50% for an assessment (by assessment)', new moodle_url('/local/data_analytics/data_ana5/'));
    $previewnode = $PAGE->navigation->add('Percentage of students receiving less than 50% for an assessment (by assessment)
', new moodle_url('/local/data_analytics/data_ana5/table.php'), navigation_node::TYPE_CONTAINER);
    $category_id = $_GET['category_id'];
    $a_parent = $DB->get_record_sql("SELECT parent FROM {course_categories} WHERE id=$category_id");
    $cat_store = new SplStack();

    if ($a_parent->parent != 0) {
        while ($a_parent->parent != 0) {

            $cat_store[] = $a_parent->parent;
            $a_parent = $DB->get_record_sql("SELECT parent FROM {course_categories} WHERE id=$a_parent->parent");
        }
    }

    foreach ($cat_store as $elem) {
        $cat_name = $DB->get_record_sql("SELECT name FROM {course_categories} WHERE id=$elem");
        $PAGE->navbar->add("$cat_name->name", new moodle_url('/local/data_analytics/data_ana1/'));
    }
    $cat_name = $DB->get_record_sql("SELECT name FROM {course_categories} WHERE id=$category_id");
    $PAGE->navbar->add("$cat_name->name");

    echo $OUTPUT->header();
}
global $DB;
$catid = $_GET['category_id'];
// Start a new PHP session
session_start(); // Start the session
$catname = $DB->get_record_sql("SELECT name FROM {course_categories} WHERE id='$catid'");
$_SESSION['catname'] = $catname->name; // Set the session variable

$DB->execute('SET @row_number = 0', array());
$fields = '@row_number := @row_number + 1 AS num,a.course, a.id , c.fullname,a.name ,a.grade';
$from = '{course} as c join {assign} as a join {course_modules} as cm ON a.id=cm.instance And c.id=a.course';
$where = "c.category=$catid and cm.module=1 AND cm.deletioninprogress=0";
$table->set_sql($fields, $from, $where);
$table->define_baseurl("$CFG->wwwroot/local/data_analytics/data_ana5/table.php?category_id=$catid");
$table->out(10, true);
// var_dump($table);
// die;
echo '
<style>
    .resettable + .pagination {
    display: none;
}
    section#region-main {
    background-image: linear-gradient(107deg,rgba(206, 255, 8, 0.048) 0%,rgba(255, 255, 255, 0.49) 35%);
}

    .no-overflow {
    overflow: auto;
   box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    border-radius: 26px;
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
