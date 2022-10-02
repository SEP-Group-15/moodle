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
$context = context_system::instance();

[$course, $cm] = get_course_and_cm_from_cmid($id, 'workflow');
$instance = $DB->get_record('workflow', ['id' => $cm->instance], '*', MUST_EXIST);

global $DB;

// $PAGE->set_url(new moodle_url('/workflow/requests_lecturer.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Student Requests');
$PAGE->set_heading('Assignment 1 - Student Requests');

$templatecontext = (object)[];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('workflow/requests_lecturer', $templatecontext);

echo $OUTPUT->footer();


// require(__DIR__.'/../../config.php');
// require_once(__DIR__.'/lib.php');

// // Course module id.
// $id = optional_param('id', 0, PARAM_INT);

// // Activity instance id.
// $w = optional_param('w', 0, PARAM_INT);

// if ($id) {
//     $cm = get_coursemodule_from_id('workflow', $id, 0, false, MUST_EXIST);
//     $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
//     $moduleinstance = $DB->get_record('workflow', array('id' => $cm->instance), '*', MUST_EXIST);
// } else {
//     $moduleinstance = $DB->get_record('workflow', array('id' => $w), '*', MUST_EXIST);
//     $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
//     $cm = get_coursemodule_from_instance('workflow', $moduleinstance->id, $course->id, false, MUST_EXIST);
// }

// require_login($course, true, $cm);

// $modulecontext = context_module::instance($cm->id);

// $event = \mod_workflow\event\course_module_viewed::create(array(
//     'objectid' => $moduleinstance->id,
//     'context' => $modulecontext
// ));
// $event->add_record_snapshot('course', $course);
// $event->add_record_snapshot('workflow', $moduleinstance);
// $event->trigger();

// $PAGE->set_url('/mod/workflow/view.php', array('id' => $cm->id));
// $PAGE->set_title(format_string($moduleinstance->name));
// $PAGE->set_heading(format_string($course->fullname));
// $PAGE->set_context($modulecontext);

// echo $OUTPUT->header();

// echo $OUTPUT->footer();
