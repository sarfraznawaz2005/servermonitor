<?php

return [
    // enable or disable server monitor.
    'enabled' => env('ENABLE_SERVER_MONITOR', true),

    // define checks for server and application that will be performed
    'checks' => [
        'server' => [

        ],

        'application' => [
            \Sarfraznawaz2005\ServerMonitor\Checks\Application\AppKeySet::class
        ]
    ],
];
