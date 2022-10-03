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

use mod_workflow\form\create;
use mod_workflow\request;
use mod_workflow\workflow;

require_once(__DIR__ . '/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB, $SESSION;

$workflowid = optional_param('workflowid', null, PARAM_INT);
// $workflow = $DB->get_record('workflow', ['id' => $workflowid]);

$PAGE->set_url(new moodle_url('/mod/workflow/create.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Create Request');
$PAGE->set_heading('Create Request');

$cmid = optional_param('cmid', true, PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($cmid, 'workflow');

// $PAGE->navbar->add($course->shortname, new moodle_url('/course/view.php', array('id' => $course->id)));
// $PAGE->navbar->add($workflow->name, new moodle_url('/mod/workflow/view.php', array('id' => $cmid)));
// $PAGE->navbar->add('Create Request');

$SESSION->workflowid = $workflowid;

$mform = new create();

if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot . '/mod/workflow/view.php?id=' . $cmid, 'Request is Cancelled');
} else if ($fromform = $mform->get_data()) {
    $SESSION->workflowid = $fromform->workflowid;
    $workflow = new workflow();
    $types['0'] = "Deadline extension";
    $types['1'] = "Failure to attempt";
    $types['2'] = "Late submission";
    $request_manager = new request();
    $wm = $workflow->getWorkflowbyCMID($cmid)->id;
    $workflowid = $workflow->getWorkflowbyCMID($cmid)->id;
    $t = time();
    $request_manager->createRequest(
        $fromform->request,
        $workflowid,
        $USER->id,
        $types[$fromform->type],
        $fromform->isbatchrequest,
        $t,
        $fromform->files,
        "",
        ""
    );

    redirect($CFG->wwwroot . '/mod/workflow/view.php?id=' . $fromform->cmid, 'Request is submitted');
}

echo $OUTPUT->header();
$temp = new stdClass();
$temp->cmid = $cmid;
$mform->set_data($temp);
$mform->display();
echo $OUTPUT->footer();
