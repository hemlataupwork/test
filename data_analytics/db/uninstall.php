<?php

function xmldb_local_data_analytics_uninstall() {
    global $DB;

    // Remove navigation item from custom menu
    $menuitems = $DB->get_record('config', array('name' => 'custommenuitems'));
    $menu = explode('
', $menuitems->value);
    $menu = array_filter($menu, function ($item) {
        return !strpos($item, '/local/data_analytics');
    });
    $DB->set_field('config', 'value', implode('
', $menu), array('name' => 'custommenuitems'));

    // // Drop table for data analytics
    // $table = new xmldb_table('data_analytics');
    // if ($DB->table_exists($table)) {
    //     $DB->drop_table($table);
    // }

    // Remove any other database schema changes or data here

    // Return true if the uninstallation was successful
    return true;
}
