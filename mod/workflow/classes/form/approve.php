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

namespace mod_workflow\form;

use moodleform;

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class approve extends moodleform
{
    public function definition()
    {
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('hidden', 'requestid');
        $mform->setType('requestid', PARAM_INT);

        $elem_request = $mform->addElement('textarea', 'request', "Request", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('request', "Enter your request");

        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'isbatchrequest', '', 'Individual', 0);
        $radioarray[] = $mform->createElement('radio', 'isbatchrequest', '', 'Batch', 1);
        $elem_radio = $mform->addGroup($radioarray, 'isbatchrequest', 'Batch/ Individual request', array(' '), false);

        $types = array();
        $types['0'] = "Deadline extension";
        $types['1'] = "Failure to attempt";
        $types['2'] = "Late submission";

        $elem_type = $mform->addElement('select', 'type', 'Select type', $types);
        $mform->setDefault('type', 0);

        $elem_file = $mform->addElement(
            'filemanager',
            'files',
            'File submission',
            null,
            array(
                'subdirs' => 0, 'maxbytes' => 50, 'areamaxbytes' => 10485760, 'maxfiles' => 50,
                'return_types' => FILE_INTERNAL | FILE_EXTERNAL
            )
        );

        $elem_instructor_comment = $mform->addElement('textarea', 'instructorcomment', "Comments by instructor", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('instructorcomment', "Enter comments regarding request");

        $validity = array();
        $validity['0'] = "Valid";
        $validity['1'] = "Reject";

        $elem_validty = $mform->addElement('select', 'validity', 'Validity', $validity);
        $mform->setDefault('validity', 0);

        $elem_request->freeze();
        $elem_radio->freeze();
        $elem_type->freeze();
        $elem_file->freeze();
        $elem_validty->freeze();
        $elem_instructor_comment->freeze();

        $elem_lec_comment = $mform->addElement('textarea', 'lec_comment', "Feedback", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('lec_comment', "Enter feedback regarding request");

        $mform->addElement('date_time_selector', 'extended_date', "Extend due date to");

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', "Approve");
        $buttonarray[] = $mform->createElement('cancel');

        $validity = array();
        $validity['0'] = "Approve";
        $validity['1'] = "Reject";

        $elem_approval = $mform->addElement('select', 'approval', '', $validity);
        $mform->setDefault('approval', 0);
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}
