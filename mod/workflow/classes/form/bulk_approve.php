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

class bulk_approve extends moodleform
{
    protected $request_ids;

    public function definition()
    {
        $mform = $this->_form;

        $mform->addElement('textarea', 'lec_comment', "Feedback", 'wrap="virtual" rows="5" cols="50"');
//        $mform->setDefault('lec_comment', "Enter feedback regarding request");

        $mform->addElement('date_time_selector', 'extended_date', "Extend due date to");



        $validity = array();
        $validity['0'] = "Approve";
        $validity['1'] = "Reject";

        $elem_approval = $mform->addElement('select', 'approval', '', $validity);
        $mform->setDefault('approval', 0);

        $this->add_action_buttons();

//        $buttonarray1 = array();
//        $buttonarray1[] = $mform->createElement('submit', 'submitbutton', "Approve");
//        $buttonarray1[] = $mform->createElement('cancel');
//        $mform->addGroup($buttonarray1, 'buttonar', '', ' ', false);
    }

    public function setRequestIds($ids){
        $this->request_ids = $ids;
    }

    public function getRequestIds() {
        return $this->request_ids;
    }
}
