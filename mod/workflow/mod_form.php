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
 * The main mod_workflow configuration form.
 *
 * @package     mod_workflow
 * @copyright   2022 SEP15
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form.
 *
 * @package     mod_workflow
 * @copyright   2022 SEP15
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_workflow_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'generalhdr', "General");
        $mform->setExpanded('generalhdr');

        $mform->addElement('text', 'name', "Workflow");
        $mform->setDefault('request',"Enter workflow name");

        $types = array();
        $types['0'] = "Activity 1";
        $types['1'] =  "Activity 2";
        $types['2'] =  "Activity 3";

        $mform->addElement('select','activity','Activity',$types);
        $mform->setDefault('activity',0);

        $types = array();
        $types['0'] = "Instructor 1";
        $types['1'] =  "Instructor 2";
        $types['2'] =  "Instructor 3";

        $mform->addElement('select','instructor','Instructor',$types);
        $mform->setDefault('instructor',0);

        $mform->addElement('header', 'availabilityhdr', "Availability");
        $mform->setExpanded('availabilityhdr');

        $mform->addElement('date_time_selector', 'startdate', "Start date", array('optional' => true));

        if (!empty($CFG->enablecourserelativedates)) {
            $attributes = [
                'aria-describedby' => 'relativedatesmode_warning'
            ];
            $relativeoptions = [
                0 => get_string('no'),
                1 => get_string('yes'),
            ];
            $relativedatesmodegroup = [];
            $relativedatesmodegroup[] = $mform->createElement('select', 'start_relativedatesmode', get_string('relativedatesmode'),
                $relativeoptions, $attributes);
            $relativedatesmodegroup[] = $mform->createElement('html', html_writer::span(get_string('relativedatesmode_warning'),
                '', ['id' => 'relativedatesmode_warning']));
            $mform->addGroup($relativedatesmodegroup, 'relativedatesmodegroup', get_string('relativedatesmode'), null, false);
        }

        $mform->addElement('date_time_selector', 'enddate', "Due date", array('optional' => true));

        if (!empty($CFG->enablecourserelativedates)) {
            $attributes = [
                'aria-describedby' => 'relativedatesmode_warning'
            ];
            $relativeoptions = [
                0 => get_string('no'),
                1 => get_string('yes'),
            ];
            $relativedatesmodegroup = [];
            $relativedatesmodegroup[] = $mform->createElement('select', 'end_relativedatesmode', get_string('relativedatesmode'),
                $relativeoptions, $attributes);
            $relativedatesmodegroup[] = $mform->createElement('html', html_writer::span(get_string('relativedatesmode_warning'),
                '', ['id' => 'relativedatesmode_warning']));
            $mform->addGroup($relativedatesmodegroup, 'relativedatesmodegroup', get_string('relativedatesmode'), null, false);
        }

        $mform->addElement('header', 'optionshdr', "Options");
        $mform->setExpanded('optionshdr');

        $mform->addElement('advcheckbox', 'commentsallowed', "Comments",
            "Allow");
        $mform->addElement('advcheckbox', 'filesallowed', "File submission",
        "Allow");

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }
}
