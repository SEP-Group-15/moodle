<?php

namespace local_workflow\form;
use moodleform;

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class request extends moodleform{
    public function definition() {

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('textarea', 'request', "Request", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('request',"Enter your request");

        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'is_batch_req', '', 'Individual', 0);
        $radioarray[] = $mform->createElement('radio', 'is_batch_req', '', 'Batch', 1);
        $mform->addGroup($radioarray, 'is_batch_req', 'Batch/ Individual request', array(' '), false);

        $types = array();
        $types['0'] = "Deadline extension";
        $types['1'] = "Informing fail to attempt";
        $types['2'] = "Late submission";

        $mform->addElement('select','type','Select type',$types);
        $mform->setDefault('type',0);

        $mform->addElement('filepicker', 'file', "File submission", null,
                   array('maxbytes' => 50, 'accepted_types' => '*'));

        $this->add_action_buttons();
    }

}