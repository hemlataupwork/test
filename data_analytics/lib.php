<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   mod_forum
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function local_data_analytics_extend_navigation(global_navigation $nav3) {
    global $CFG, $PAGE, $DB, $USER;
    $nav3->add(
    'Data Analytics',
    new moodle_url($CFG->wwwroot . '/local/data_analytics/index.php'),
    navigation_node::TYPE_SYSTEM,
    null,
    'local_data_analytics',
    $icon
    )->showinflatnavigation = true;
}


function local_data_analytics_before_standard_html_head() {
    
    global  $USER,$CFG,$DB;

    $is_role=user_has_role_assignment($USER->id, 3);


     if($is_role){
         return '<script>
     setInterval(duration,2000);
       
        function duration(){
        
            $.ajax({
             method:"post",
             async:false,
             dataType:"json",
             url:"'.$CFG->wwwroot . '/local/data_analytics/time_spend.php'.'",
             success:function(json){
             }
           });           
        }
</script>';

     
   
}
}


