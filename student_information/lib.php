<?php
function local_student_information_extend_navigation(global_navigation $nav)
{
    
    global $CFG, $PAGE,$DB,$USER;
    $icon = new pix_icon('user_icon', '', 'local_student_information', array('class' => 'icon pluginicon'));
    if(is_siteadmin())
    {
        $nav->add(
            'Student Information',
            new moodle_url($CFG->wwwroot . '/local/student_information/index.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_student_information',
            $icon,
        )->showinflatnavigation = true;
    }
}