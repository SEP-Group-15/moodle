<?php
$capabilities = [
    'mod/workflow:addinstance' => [
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/course:manageactivities',
    ],
    // 'mod/workflow:view' => array(
    //     'captype' => 'read',
    //     'contextlevel' => CONTEXT_MODULE,
    //     'archetypes' => array(
    //         'guest' => CAP_ALLOW,
    //         'student' => CAP_ALLOW,
    //         'teacher' => CAP_ALLOW,
    //         'editingteacher' => CAP_ALLOW,
    //         'manager' => CAP_ALLOW,
    //     ),
    // ),
    'mod/workflow:createrequest' => array(
        // 'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'student' => CAP_ALLOW,
        ),
    ),
];
