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

use local_workflow\request_manager;

require_once(__DIR__ . '/../../config.php'); // setup moodle
require_login();
$context = context_system::instance();

global $DB;
$PAGE->set_url(new moodle_url('/local/workflow/requests_lecturer.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Student Requests');
$PAGE->set_heading('Assignment 1 - Student Requests');

$request_manager = new request_manager();
$requests = $request_manager->getAllRequests();
$templatecontext = (object)[
    'requests'=>array_values($requests),
    'url' => $CFG->wwwroot.'/local/workflow/approve.php?requestid='
];


echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_workflow/requests_lecturer', $templatecontext);

echo $OUTPUT->footer();
