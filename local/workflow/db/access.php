<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = [

    'local/workflow:approverequests' => [
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'lecturer' => CAP_ALLOW,
        ],
    ],
    
    'local/workflow:validaterequests' => [
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'instructor' => CAP_ALLOW,
        ],
    ],    
];