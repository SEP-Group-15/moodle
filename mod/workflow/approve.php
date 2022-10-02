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

require_once(__DIR__ . '/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB;
$PAGE->set_url(new moodle_url('/mod/workflow/approve.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Validate request');
$PAGE->set_heading('Student Request');

$requestid = optional_param('id', null, PARAM_INT);
$edit = optional_param('edit', true, PARAM_BOOL);
$cmid = optional_param('cmid', true, PARAM_INT);

$mform = new approve();

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
    redirect($CFG->wwwroot . '/mod/workflow/view.php?id='.$cmid, 'Approving is Cancelled');
} else if ($fromform = $mform->get_data()) {
    $request_manager = new request();
    $status['0'] = "approved";
    $status['1'] = "rejected";
    $request_manager->approve(
        $fromform->id,
        $status[$fromform->approval],
        $fromform->lec_comment
    );

    redirect($CFG->wwwroot . '/mod/workflow/view.php?id='.$fromform->cmid, 'Request is approved');
}

echo $OUTPUT->header();
// var_dump($);
// die;
$mform->display();
echo $OUTPUT->footer();
