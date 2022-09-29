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

use local_workflow\form\addworkflow;
use local_workflow\workflow;

require_once(__DIR__ .'/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB;
$PAGE->set_url(new moodle_url('/local/workflow/addworkflow.php'));
$PAGE->set_context (\context_system::instance());
$PAGE->set_title('Create workflow');
$PAGE->set_heading('Create workflow');

$mform = new addworkflow();

if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot.'/local/workflow/request.php','Workflow creation is cancelled');
} else if ($fromform = $mform->get_data()) {    
    $types['0'] = "Deadline extension";
    $types['1'] = "Failure to attempt";
    $types['2'] = "Late submission";
    $request_manager = new workflow();
    $workflowid = 1;
    $t = time();
    $timecreated = date("Y-m-d H:i:s",$t);
    $request_manager->create(
        $fromform->name,
        "100",
        '100',
        '200',
        $fromform->startdate,
        $fromform->enddate,
        $fromform->commentsallowed,
        $fromform->filesallowed
    );
    
    redirect($CFG->wwwroot.'/local/workflow/addworkflow.php','Workflow is created');
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();