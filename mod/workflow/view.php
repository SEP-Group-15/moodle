<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_workflow.
 *
 * @package     mod_workflow
 * @copyright   2022 SEP15
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require('../../config.php');

$id = required_param('id', PARAM_INT);

require_login();

[$course, $cm] = get_course_and_cm_from_cmid($id, 'workflow');
// $cm = get_coursemodule_from_id('workflow', $id, $course->id, false, MUST_EXIST);
$instance = $DB->get_record('workflow', ['id' => $cm->instance], '*', MUST_EXIST);
$context = context_module::instance($cm->id);

global $DB;

// $PAGE->set_url(new moodle_url('/workflow/requests.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Student Requests');
$PAGE->set_heading('Assignment 1 - Student Requests');

echo $OUTPUT->header();

echo '<pre>';
var_dump($SESSION);
echo '</pre>';
die();

if (has_capability('mod/workflow:createrequest', $context)) {
    echo '<button class="btn btn-primary">Create Request</button>';
}

echo $OUTPUT->render_from_template('workflow/requests_list', null);

echo $OUTPUT->footer();
