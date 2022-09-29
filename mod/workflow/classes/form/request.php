<?php

namespace mod_workflow\form;
use moodleform;

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class request extends moodleform{
    public function definition() {

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('textarea', 'request', "Request", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('request',"Enter your request");

        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'isbatchrequest', '', 'Individual', 0);
        $radioarray[] = $mform->createElement('radio', 'isbatchrequest', '', 'Batch', 1);
        $mform->addGroup($radioarray, 'isbatchrequest', 'Batch/ Individual request', array(' '), false);

        $types = array();
        $types['0'] = "Deadline extension";
        $types['1'] = "Failure to attempt";
        $types['2'] = "Late submission";

        $mform->addElement('select','type','Select type',$types);
        $mform->setDefault('type',0);

        $mform->addElement('filemanager', 'files', 'File submission', null,
        array('subdirs' => 1,'maxfiles'=>50,'accepted_types' => '*'));
        $this->add_action_buttons();
    }

}