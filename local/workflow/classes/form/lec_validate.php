<?php

namespace local_workflow\form;
use moodleform;

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class lec_validate extends moodleform{
    public function definition() {

        $mform = $this->_form; // Don't forget the underscore!

        $elem_request = $mform->addElement('textarea', 'request', "Request", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('request',"Enter your request");

        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'is_batch_rep', '', 'Individual', 0);
        $radioarray[] = $mform->createElement('radio', 'is_batch_rep', '', 'Batch', 1);
        $elem_radio = $mform->addGroup($radioarray, 'is_batch_rep', 'Batch/ Individual request', array(' '), false);

        $types = array();
        $types['0'] = "Deadline extension";
        $types['1'] = "Informing fail to attempt";
        $types['2'] = "Late submission";

        $elem_type = $mform->addElement('select','type','Select type',$types);
        $mform->setDefault('type',0);

        $elem_file = $mform->addElement('filepicker', 'file', "File submission", null,
                   array('maxbytes' => 50, 'accepted_types' => '*'));        

        $elem_instructor_comment = $mform->addElement('textarea', 'instructor_comment', "Comments by instructor", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('instructor_comment',"Enter comments regarding request");

        $validity = array();
        $validity['0'] = "Valid";
        $validity['1'] = "Reject";

        $elem_validty = $mform->addElement('select','validity','Validity',$validity);
        $mform->setDefault('validty',0);

        $elem_request->freeze();
        $elem_radio->freeze();
        $elem_type->freeze();
        $elem_file->freeze();
        $elem_validty->freeze();
        $elem_instructor_comment->freeze();

        $elem_lec_comment = $mform->addElement('textarea', 'lec_comment', "Feedback", 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('instructor_comment',"Enter feedback regarding request");

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', "Approve");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

}