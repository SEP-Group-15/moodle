<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = [

    'local/workflow:approverequests' => [
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
        ],
    ],
    
    'local/workflow:validaterequests' => [
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'teacher' => CAP_ALLOW,
        ],
    ],    
];