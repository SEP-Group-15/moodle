<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_workflow.
 *
 * @package     mod_workflow
 * @copyright   2022 SEP15
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


// require('../../config.php');
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/course/format/lib.php');

use mod_workflow\request;

global $USER;

$id = required_param('id', PARAM_INT);

require_login();

[$course, $cm] = get_course_and_cm_from_cmid($id, 'workflow');
// $cm = get_coursemodule_from_id('workflow', $id, $course->id, false, MUST_EXIST);
$instance = $DB->get_record('workflow', ['id' => $cm->instance], '*', MUST_EXIST);
$context = context_course::instance($course->id);

global $DB;

$roleid = $DB->get_field_select('role_assignments', 'roleid', 'contextid = :contextid and userid=:userid', [
    'contextid' => $context->id,
    'userid' => $USER->id,
]);

$role = $DB->get_field_select('role', 'shortname', 'id=:id', [
    'id' => $roleid
]);


// $PAGE->set_url(new moodle_url('/workflow/requests.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Student Requests');
$PAGE->set_heading('Assignment 1 - Student Requests');

echo $OUTPUT->header();

$request_manager = new request();
$requests = $request_manager->getAllRequests();

if ($role == "student") {
    $requests = $request_manager->getRequestsByStudentId_cmid($USER->id,$cm->id);
    $templatecontext = (object)[
        'requests' => array_values($requests),
        'text' => 'text',
        'url' => $CFG->wwwroot . '/mod/workflow/validate.php?id=',
        'cmid'=>$cm->id,
    ];
    $createurl = $CFG->wwwroot . '/mod/workflow/create.php?cmid='.$cm->id;
    echo '<a class="btn btn-primary" href="' . $createurl . '">Create New Request</a>';
    echo $OUTPUT->render_from_template('mod_workflow/requests_student', $templatecontext);
} else if ($role == "teacher") {
    $requests = $request_manager->getAllRequestsByWorkflow($cm->id);
    $templatecontext = (object)[
        'requests' => array_values($requests),
        'text' => 'text',
        'url' => $CFG->wwwroot . '/mod/workflow/validate.php?id=',
        'cmid'=>$cm->id,
    ];
    echo $OUTPUT->render_from_template('mod_workflow/requests_instructor', $templatecontext);
} else if ($role == "editingteacher" || $role = "manager") {
    $requests = $request_manager->getAllRequestsByWorkflow($cm->id);
    $templatecontext = (object)[
        'requests' => array_values($requests),
        'url' => $CFG->wwwroot . '/mod/workflow/approve.php?id=',
        'cmid'=>$cm->id,
    ];
    echo $OUTPUT->render_from_template('mod_workflow/requests_lecturer', $templatecontext);
}

echo $OUTPUT->footer();
