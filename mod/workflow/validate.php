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

use mod_workflow\form\validate;
use mod_workflow\request;

require_once(__DIR__ . '/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();
// require_capability('local/workflow:validaterequests',$context);

global $DB;
$PAGE->set_url(new moodle_url('/mod/workflow/validate.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Validate request');
$PAGE->set_heading('Student Request');

$id = optional_param('id', null, PARAM_INT);
$edit = optional_param('edit', true, PARAM_BOOL);

$mform = new validate();

if ($mform->is_cancelled()) {
    //go back to manage page
    redirect($CFG->wwwroot . '/mod/workflow/validate.php', 'Validation is Cancelled');
} else if ($fromform = $mform->get_data()) {
    var_dump($fromform);
    die();
    $request_manager = new request();
    $validity['0'] = "valid";
    $validity['1'] = "rejected";
    $request_manager->validate(
        $fromform->id,
        $validity[$fromform->validity],
        $fromform->instructor_comment,
    );
    redirect($CFG->wwwroot . '/mod/course', 'Request is validated');
}

if ($id) {
    $types = [
        "Deadline extension" => '0',
        "Failure to attempt" => '1',
        "Late submission" => '2',
    ];
    $request_manager = new request();
    $request = $request_manager->getRequest($id);
    $request->type = $types[$request->type];
    if (!$request) {
        die("Request");
        \core\notification::add('Request not found', \core\output\notification::NOTIFY_WARNING);
    }
    $mform->set_data($request);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
