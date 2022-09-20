<?php

namespace local_workflow\form;
use moodleform;

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class validate extends moodleform{
    public function definition() {

        $mform = $this->_form; // Don't forget the underscore!

        $elem_request = $mform->addElement('textarea', 'request', "Request", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('request',"Enter your request");

        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'isbatchrequest', '', 'Individual', 0);
        $radioarray[] = $mform->createElement('radio', 'isbatchrequest', '', 'Batch', 1);
        $elem_radio = $mform->addGroup($radioarray, 'isbatchrequest', 'Batch/ Individual request', array(' '), false);

        $types = array();
        $types['0'] = "Deadline extension";
        $types['1'] = "Failure to attempt";
        $types['2'] = "Late submission";

        $elem_type = $mform->addElement('select','type','Select type',$types);
        $mform->setDefault('type',0);

        $elem_file = $mform->addElement('filemanager', 'files', 'File submission', null,
        array('subdirs' => 0, 'maxbytes' => 50, 'areamaxbytes' => 10485760, 'maxfiles' => 50, 
        'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));

        $elem_request->freeze();
        $elem_radio->freeze();
        $elem_type->freeze();
        $elem_file->freeze();

        

        $mform->addElement('textarea', 'instructor_comment', "Comments", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('instructor_comment',"Enter comments regarding request");

        $validity = array();
        $validity['0'] = "Valid";
        $validity['1'] = "Reject";

        $elem_validty = $mform->addElement('select','validity','',$validity);
        $mform->setDefault('type',0);

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', "Submit");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

}