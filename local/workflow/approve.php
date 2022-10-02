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

use local_workflow\form\approve;
use local_workflow\request_manager;

require_once(__DIR__ .'/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB;
$PAGE->set_url(new moodle_url('/local/workflow/approve.php'));
$PAGE->set_context (\context_system::instance());
$PAGE->set_title('Validate request');
$PAGE->set_heading('Student Request');

$requestid = optional_param('requestid',null,PARAM_INT);
$edit = optional_param('edit', true, PARAM_BOOL);

$mform = new approve();

if ($requestid){

    $types =[
        "Deadline extension"=>'0',
        "Failure to attempt"=>'1',
        "Late submission"=>'2',
    ];
    $request_manager = new request_manager();
    $request = $request_manager->getRequest($requestid);
    $request->type = $types[$request->type];
    if (!$request){
        die("Request");
        \core\notification::add('Request not found', \core\output\notification::NOTIFY_WARNING);
    }
    $mform->set_data($request);
}


if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot.'/local/workflow/approve.php','Approving is Cancelled');
} else if ($fromform = $mform->get_data()) {    
    $request_manager = new request_manager();
    $status['0'] = "approved";
    $status['1'] = "rejected";
    $request_manager->approve(
        $fromform->requestid,
        $status[$fromform->validity],
        $fromform->lec_comment
    );
    
    redirect($CFG->wwwroot.'/local/workflow/approve.php','Request is approved');
}

echo $OUTPUT->header();
// var_dump($);
// die;
$mform->display();
echo $OUTPUT->footer();