<?php

return [

    // enable or disable server monitor
    'enabled' => env('ENABLE_SERVER_MONITOR', true),

    // define name(s) of production environment you use
    'production_environments' => ['production', 'prod', 'live'],

    // define checks for server and application that will be performed
    'checks' => [

        // these checks are for server only
        'server' => [
        ],

        // these checks are for application only. These checks run in order as specified here.
        // You can comment out ones not needed.
        'application' => [

            // common checks that will run on all environments
            'common' => [
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\CorrectPhpVersionInstalled::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\EnvFileExists::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\AppKeySet::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\DirectoriesHaveCorrectPermissions::class => [
                    // paths to check permissions of
                    'paths' => [
                        storage_path(),
                        base_path('bootstrap/cache'),
                    ]
                ],
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\DBCanBeAccessed::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\MigrationsAreUpToDate::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\ComposerDependenciesUpToDate::class => [
                    // path to composer binary
                    'binary_path' => 'composer'
                ],
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\StorageDirectoryIsLinked::class,
            ],

            // checks that will run only on non-production environments
            'development' => [
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\DebugModeOn::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\ConfigNotCached::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\RoutesAreNotCached::class,
            ],

            // checks that will run only on production environment
            'production' => [
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\DebugModeOff::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\ConfigCached::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\RoutesAreCached::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\PhpExtensionsAreDisabled::class => [
                    'extensions' => [
                        'xdebug',
                    ],
                ],
            ]
        ],
    ],

];
