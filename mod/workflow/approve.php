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
 * Version details
 *
 * @package    mod_workflow
 * @copyright  2022 SEP15
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_workflow\form\approve;
use mod_workflow\request;
use mod_workflow\message_handler;

require_once(__DIR__ . '/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB;
$PAGE->set_url(new moodle_url('/mod/workflow/approve.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Approve request');
$PAGE->set_heading('Approve Request');

$requestid = optional_param('id', null, PARAM_INT);
$edit = optional_param('edit', true, PARAM_BOOL);
$cmid = optional_param('cmid', true, PARAM_INT);
$workflowid = optional_param('workflowid', null, PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($cmid, 'workflow');

// $PAGE->navbar->add($course->shortname, new moodle_url('/course/view.php', array('id' => $course->id)));
// $workflow = $DB->get_record('workflow', ['id' => $workflowid]);
// $PAGE->navbar->add($workflow->name, new moodle_url('/mod/workflow/view.php', array('id' => $cmid)));
// $PAGE->navbar->add('Create Request');

$mform = new approve();
$msg_handler = new message_handler();

if ($requestid) {

    $types = [
        "Deadline extension" => '0',
        "Failure to attempt" => '1',
        "Late submission" => '2',
    ];
    $request_manager = new request();
    $request = $request_manager->getRequest($requestid);
    $request->type = $types[$request->type];
    $request->cmid = $cmid;
    if (!$request) {
        die("Request");
        \core\notification::add('Request not found', \core\output\notification::NOTIFY_WARNING);
    }
    $mform->set_data($request);
}


if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot . '/mod/workflow/view.php?id=' . $cmid, 'Approving is Cancelled');
} else if ($fromform = $mform->get_data()) {
    $request_manager = new request();
    $status['0'] = "approved";
    $status['1'] = "rejected";
    $request_manager->approve(
        $fromform->id,
        $status[$fromform->approval],
        $fromform->lec_comment
    );
    $msg_handler->send($fromform->studentid, 'Your request ' . $fromform->id . ' is ' . ucwords($status[$fromform->approval]), $cmid);

    redirect($CFG->wwwroot . '/mod/workflow/view.php?id=' . $fromform->cmid, 'Request is approved');
}

echo $OUTPUT->header();
// var_dump($);
// die;
$mform->display();
echo $OUTPUT->footer();
