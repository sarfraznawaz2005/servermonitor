<?php

return [

    #-------------------------------------------------------------------
    # Enable or disable Server Monitor
    'enabled' => env('ENABLE_SERVER_MONITOR', true),
    #-------------------------------------------------------------------

    #-------------------------------------------------------------------
    # Route where Server Monitor will be available in your app.
    'route' => 'servermonitor',
    #-------------------------------------------------------------------

    #-------------------------------------------------------------------
    # If "true", the Server Monitor page can be viewed by any user who provides
    # correct login information (eg all app users).
    'http_authentication' => false,
    #-------------------------------------------------------------------

    #-------------------------------------------------------------------
    # Define name(s) of production environment you use
    #-------------------------------------------------------------------
    'production_environments' => ['production', 'prod', 'live'],
    #-------------------------------------------------------------------

    #-------------------------------------------------------------------
    # Define checks for server and application that will be performed
    'checks' => [

        // These checks are for server only
        'server' => [
        ],

        // These checks are for application only. These checks run in order as specified here.
        // You can comment out ones not needed.
        'application' => [

            // common checks that will run on all environments
            'common' => [
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\CorrectPhpVersionInstalled::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\EnvFileExists::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\AppKeySet::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\DirectoriesHaveCorrectPermissions::class => [
                    // Paths to check permissions of
                    'paths' => [
                        storage_path(),
                        base_path('bootstrap/cache'),
                    ]
                ],
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\DBCanBeAccessed::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\MigrationsAreUpToDate::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\ComposerDependenciesUpToDate::class => [
                    // Path to composer binary
                    'binary_path' => 'composer'
                ],
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\StorageDirectoryIsLinked::class,
            ],

            // Checks that will run only on non-production environments
            'development' => [
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\DebugModeOn::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\ConfigNotCached::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\RoutesAreNotCached::class,
            ],

            // Checks that will run only on production environment
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
    #-------------------------------------------------------------------

];
