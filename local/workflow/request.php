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
 * @package    local_workflow
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_workflow\form\request;
use local_workflow\request_manager;

require_once(__DIR__ .'/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB;
$PAGE->set_url(new moodle_url('/local/workflow/request.php'));
$PAGE->set_context (\context_system::instance());
$PAGE->set_title('Submit request');
$PAGE->set_heading('Student Request');

$mform = new request();

if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot.'/local/workflow/request.php','Request is Cancelled');
} else if ($fromform = $mform->get_data()) {    
    $types['0'] = "Deadline extension";
    $types['1'] = "Failure to attempt";
    $types['2'] = "Late submission";
    $request_manager = new request_manager();
    $workflowid = 1;
    $t = time();
    $timecreated = date("Y-m-d H:i:s",$t);
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
    
    redirect($CFG->wwwroot.'/local/workflow/request.php','Request is submitted');
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();