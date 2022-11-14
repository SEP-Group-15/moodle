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

use mod_workflow\request;
use mod_workflow\workflow;

// require('../../config.php');
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/course/format/lib.php');
require_login();

global $USER, $DB;

$id = required_param('id', PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($id, 'workflow');
$instance = $DB->get_record('workflow', ['id' => $cm->instance], '*', MUST_EXIST);
$context = context_module::instance($cm->id);
$workflow = $DB->get_record('workflow', ['id' => $cm->instance]);

$PAGE->set_url(new moodle_url('/mod/workflow/view.php'));
$PAGE->set_context($context);
$PAGE->set_title($course->shortname . ': ' . $workflow->name);
$PAGE->set_heading($workflow->name);
$PAGE->set_cm($cm, $course);

$cap_approve = has_capability('mod/workflow:approverequest', $context);
$cap_validate = has_capability('mod/workflow:validaterequest', $context);
$cap_create = has_capability('mod/workflow:createrequest', $context);

echo $OUTPUT->header();

$request_manager = new request();
$workflow = new workflow();
$requests = $request_manager->getAllRequests();
$cmid = $cm->id;

if ($cap_approve) {
    $workflowid = $workflow->getWorkflowbyCMID($cmid)->id;
    $requests = $request_manager->getValidRequestsByWorkflow($workflowid);
    $requests = $request_manager->processRequests($requests);
    $templatecontext = (object)[
        'requests' => array_values($requests),
        'url' => $CFG->wwwroot . '/mod/workflow/approve.php?id=',
        'cmid' => $cmid,
    ];
    echo $OUTPUT->render_from_template('mod_workflow/requests_lecturer', $templatecontext);
} else if ($cap_validate) {
    $workflowid = $workflow->getWorkflowbyCMID($cmid)->id;
    $requests = $request_manager->getRequestsByWorkflow($workflowid);
    $requests = $request_manager->processRequests($requests);
    $templatecontext = (object)[
        'requests' => array_values($requests),
        'text' => 'text',
        'url' => $CFG->wwwroot . '/mod/workflow/validate.php?id=',
        'cmid' => $cmid,
    ];
    echo $OUTPUT->render_from_template('mod_workflow/requests_instructor', $templatecontext);
} else if ($cap_create) {
    $workflowid = $workflow->getWorkflowbyCMID($cmid)->id;
    $requests = $request_manager->getRequestsByWorkflow_Student($USER->id, $workflowid);
    $requests = $request_manager->processRequests($requests);
    $templatecontext = (object)[
        'requests' => array_values($requests),
        'text' => 'text',
        'url' => $CFG->wwwroot . '/mod/workflow/validate.php?id=',
        'cmid' => $cmid,
    ];
    $createurl = $CFG->wwwroot . '/mod/workflow/create.php?cmid=' . $cmid . '&workflowid=' . $workflowid;
    echo '<a class="btn btn-primary" href="' . $createurl . '">Create New Request</a>';
    echo $OUTPUT->render_from_template('mod_workflow/requests_student', $templatecontext);
}

echo $OUTPUT->footer();
