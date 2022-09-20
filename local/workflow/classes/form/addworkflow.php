<?php

namespace local_workflow\form;
use moodleform;

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class addworkflow extends moodleform{

    public function definition() {

        $mform = $this->_form; // Don't forget the underscore!

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

        $this->add_action_buttons();

    }

}