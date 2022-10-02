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

require_once(__DIR__ . '/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB;
$PAGE->set_url(new moodle_url('/mod/workflow/create.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Submit request');
$PAGE->set_heading('Student Request');

$cmid = optional_param('cmid', true, PARAM_INT);

$mform = new create();

if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot . '/mod/workflow/view.php?id='.$cmid, 'Request is Cancelled');
} else if ($fromform = $mform->get_data()) {
    $types['0'] = "Deadline extension";
    $types['1'] = "Failure to attempt";
    $types['2'] = "Late submission";
    $request_manager = new request();
    $workflowid = $cmid;
    $t = time();
    $timecreated = date("Y-m-d H:i:s", $t);
    $request_manager->createRequest(
        $fromform->request,
        $workflowid,
        $USER->id,
        $types[$fromform->type],
        $fromform->isbatchrequest,
        $fromform->files,
        "",
        ""
    );

    redirect($CFG->wwwroot . '/mod/workflow/view.php?id='.$fromform->cmid, 'Request is submitted');
}

echo $OUTPUT->header();

$temp = new stdClass();
$temp->cmid = $cmid;
$mform->set_data($temp);
$mform->display();
echo $OUTPUT->footer();
