<?php

function xmldb_local_student_information_install() {
    global $DB;

    // Add navigation item to custom menu
    $menuitems = $DB->get_record('config', array('name' => 'custommenuitems'));
    $menu = explode('
', $menuitems->value);
    $menu[] = 'Student Information|/local/student_information/';
    $DB->set_field('config', 'value', implode('
', $menu), array('name' => 'custommenuitems'));

    $max_sortorder=$DB->get_record_sql("SELECT max(sortorder) as sortorder FROM {user_info_field}");
    $user_info_field = new stdClass();
    $user_info_field->shortname = 'email2';
    $user_info_field->name = 'Email 2';
    $user_info_field->datatype = 'text';
    $user_info_field->descriptionformat = 1;
    $user_info_field->categoryid = 1;
    $user_info_field->sortorder = ($max_sortorder->sortorder + 1);
    $user_info_field->visible = 2;
    $user_info_field->forceunique = 1;
    $user_info_field->signup = 1;
    $user_info_field->param1 = 30;
    $user_info_field->param2 = 248;
    $DB->insert_record('user_info_field', $user_info_field);

    return true;
}


