<?php

defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => 'core\event\course_viewed',
        'callback' => '\local_student_information\observers::attendance1',
    ),
);