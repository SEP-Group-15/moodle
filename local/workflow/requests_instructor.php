<?php

require_once(__DIR__ . '/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB;
$PAGE->set_url(new moodle_url('/local/workflow/requests_instructor.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Student Requests');
$PAGE->set_heading('Assignment 1 - Student Requests');

$templatecontext = (object)[
    'text' => 'text'
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_workflow/requests_instructor', $templatecontext);

echo $OUTPUT->footer();
