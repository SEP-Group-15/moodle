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

use mod_workflow\form\bulk_approve;
use mod_workflow\request;
use mod_workflow\message_handler;

require_once(__DIR__ . '/../../config.php'); // setup moodle
require_login();

global $DB;

$edit = optional_param('edit', true, PARAM_BOOL);
$cmid = optional_param('cmid', true, PARAM_INT);
$workflowid = optional_param('workflowid', null, PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($cmid, 'workflow');
$context = context_module::instance($cm->id);


$PAGE->set_url(new moodle_url('/mod/workflow/bulk.php'));
$PAGE->set_context($context);
$PAGE->set_title('Approve bulk request');
$PAGE->set_heading('Approve Bulk Requests');
$PAGE->navbar->add('Approve Bulk Request');
$PAGE->set_cm($cm, $course);

$mform = new bulk_approve();

$request_ids = array();
if (isset($_POST)) {
    foreach ($_POST as $elem => $sel) {
//        $elem format : req-id-<requestid>
        $request_ids[] = substr($elem, 7);
    }
}

if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot . '/mod/workflow/view.php?id=' . $cmid, 'Approving is Cancelled');
//} else if(!($mform->is_submitted())) {
//    $request_manager = new request();
//    $status['0'] = "approved";
//    $status['1'] = "rejected";
//    foreach ($request_ids as $id) {
//        $request_manager->approve(
//            $id,
//            $status[0],
//            ''
//        );
//
//        if (1) {
//
//            $activityid = $request_manager->getActivityId($id);
//            $request_manager->processExtensions(
//                $activityid,
//                $request_manager->getStudentID($id),
//                1668981900,
//                ''
//            );
//        }
////        $msg_handler->send($fromform->studentid, 'Your request ' . $fromform->id . ' is ' . ucwords($status[$fromform->approval]), $cmid);
//    }
//    redirect($CFG->wwwroot . '/mod/workflow/view.php?id=' . $fromform->cmid, 'Request is approved');
//}
}else if ($fromform = $mform->get_data()) {

    print_r($fromform);
    die;

    $request_manager = new request();
    $status['0'] = "approved";
    $status['1'] = "rejected";

    foreach ($request_ids as $id) {

        $request_manager->approve(
            $id,
            $status[$fromform->approval],
            $fromform->lec_comment
        );

        if ($status[$fromform->approval] === "approved") {

            $activityid = $request_manager->getActivityId($id);
            $request_manager->processExtensions(
                $activityid,
                $fromform->studentid,
                $fromform->extended_date,
                $fromform->type
            );
        }
//        $msg_handler->send($fromform->studentid, 'Your request ' . $fromform->id . ' is ' . ucwords($status[$fromform->approval]), $cmid);
    }
    redirect($CFG->wwwroot . '/mod/workflow/view.php?id=' . $fromform->cmid, 'Request is approved');
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
