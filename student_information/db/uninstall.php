<?php

function xmldb_local_student_information_uninstall() {
    global $DB;

    // Remove navigation item from custom menu
    $menuitems = $DB->get_record('config', array('name' => 'custommenuitems'));
    $menu = explode('
', $menuitems->value);
    $menu = array_filter($menu, function ($item) {
        return !strpos($item, '/local/student_information/');
    });
    $DB->set_field('config', 'value', implode('
', $menu), array('name' => 'custommenuitems'));
    return true;
}
