<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable Server Monitor
    |--------------------------------------------------------------------------
    |
    | Enable or disable Server Monitor
    |
    */
    'enabled' => env('ENABLE_SERVER_MONITOR', true),

    /*
    |--------------------------------------------------------------------------
    | Web Interface
    |--------------------------------------------------------------------------
    |
    | Define if web interface will be enabled, its route where Server Monitor
    | will be available in your app and basic auth login details.
    |
    */
    'web_interface_enabled' => true,
    'route' => 'servermonitor',
    'username' => 'servermonitor',
    'password' => 'servermonitor',


    /*
    |--------------------------------------------------------------------------
    | Production Environment Name
    |--------------------------------------------------------------------------
    |
    | Define name(s) of production environment you use
    |
    */
    'production_environments' => ['production', 'prod', 'live'],

    /*
    |--------------------------------------------------------------------------
    | Application & Server Checks
    |--------------------------------------------------------------------------
    |
    | Define checks for server and application that will be performed.
    |
    | Some checks are commented intentionally, you can un-comment them if
    | you need to use them.
    |
    */
    'checks' => [

        // These checks are for server only
        'server' => [
            \Sarfraznawaz2005\ServerMonitor\Checks\Server\RequiredPhpExtensionsAreInstalled::class,
            \Sarfraznawaz2005\ServerMonitor\Checks\Server\DiskSpaceEnough::class => [
                'fail_percentage' => 90
            ],

            /*
            \Sarfraznawaz2005\ServerMonitor\Checks\Server\CheckPhpIniValues::class => [
                'checks' => [
                    'max_execution_time' => '36000',
                    'memory_limit' => '512M',
                    'display_errors' => '1',
                    'error_reporting' => '32767',
                ]
            ],
            */

            /*
            \Sarfraznawaz2005\ServerMonitor\Checks\Server\FtpConnectionWorks::class => [
                'servers' => [
                    'myserver' => [
                        'host' => 'ftp.yourdomain.com',
                        'username' => 'username',
                        'password' => 'password',
                        'port' => 21,
                        'timeout' => 10,
                        'passive' => true,
                        'ssl' => false
                    ],
                ]
            ],
            */

            /*
            // requires "league/flysystem-sftp" package.
            \Sarfraznawaz2005\ServerMonitor\Checks\Server\SftpConnectionWorks::class => [
                'servers' => [
                    'myserver' => [
                        'host' => 'ftp.yourdomain.com',
                        'username' => 'username',
                        'password' => 'password',
                        'privateKey' => 'path/to/or/contents/of/privatekey',
                        'port' => 22,
                        'timeout' => 10,
                        'ssl' => false
                    ],
                ]
            ],
            */

            /*
            \Sarfraznawaz2005\ServerMonitor\Checks\Server\SslCertificateValid::class => [
                'url' => 'https://yourdomain.com'
            ],
            */

            /*
            \Sarfraznawaz2005\ServerMonitor\Checks\Server\ServersArePingable::class => [
                'servers' => [
                    [
                        'host' => 'www.google.com',
                        'port' => null,
                        'timeout' => 5
                    ],
                ]
            ],
            */

            /*
             \Sarfraznawaz2005\ServerMonitor\Checks\Server\HttpStatusCode::class => [
                 'sites' => [
                     'google' => ['url' => 'http://google.com', 'expected_code' => 200],
                 ]
             ],
             */

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
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\DatabaseCanBeAccessed::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\MigrationsAreUpToDate::class,
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\ComposerDependenciesUpToDate::class => [
                    // Path to composer binary
                    'binary_path' => 'composer'
                ],

                \Sarfraznawaz2005\ServerMonitor\Checks\Application\StorageDirectoryIsLinked::class,

                /*
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\CorrectEnvValues::class => [
                    'checks' => [
                        'local' => [
                            'path' => config_path('app.php'),
                            'expected_values' => [
                                'env' => 'local',
                                'debug' => true,
                                'url' => 'http://localhost',
                            ]
                        ],
                        'production' => [
                            'path' => config_path('app.php'),
                            'expected_values' => [
                                'env' => 'production',
                                'debug' => false,
                                'url' => 'http://mysite.com',
                            ]
                        ],
                    ]
                ],
                */

                /*
                // requires "sensiolabs/security-checker" package.
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\ComposerPackagesSecurity::class,
                */

                /*
                // requires "Predis\Client" package.

                \Sarfraznawaz2005\ServerMonitor\Checks\Application\RedisCanBeAccessed::class => [
                    'default_connection' => true,
                    'connections' => [],
                ],
                */

                /*
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\MailWorks::class => [
                    'mail_config' => [
                        'driver' => config('mail.driver'),
                        'host' => config('mail.host'),
                        'port' => config('mail.port'),
                        'from' => [
                            'address' => config('mail.from.address'),
                            'name' => config('mail.from.name'),
                        ],
                        'encryption' => config('mail.encryption'),
                        'username' => config('mail.username'),
                        'password' => config('mail.password'),
                        'sendmail' => config('mail.sendmail'),
                    ],
                    'to' => 'someone@example.com',
                    'subject' => 'Server Monitor Test Mail',
                    'content' => 'Hello World!',
                ],
                */

                /*
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\CloudStorage::class => [
                    'disks' => [
                        's3'
                    ],
                    'file' => 'dummy.txt',
                    'content' => str_random(32),
                ],
                */
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
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\UnwantedPhpExtensionsAreDisabled::class => [
                    'extensions' => [
                        'xdebug',
                    ],
                ],

                /*
                \Sarfraznawaz2005\ServerMonitor\Checks\Application\SupervisorProgramsAreRunning::class => [
                    'programs' => [
                        'horizon',
                    ],
                    'restarted_within' => 300
                ],
                */
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Setup Notification
    |--------------------------------------------------------------------------
    | Setup notification options, how you want to get notified and other details.
    |
    | Note that you can overridd all below options for individual check notification
    | like so:
    |
    |   \Sarfraznawaz2005\ServerMonitor\Checks\Application\AppKeySet::class => [
    |        'notification_channel' => 'log'
    |        'notification_notify_emails' => ['foo@example.com']
    |        'disable_notification' => false // enable/disable notification for this check only.
    |   ]
    |
    | NOTE: For Laravel <= 5.1, we only send simple email because notifications
    | are were not built into those versions.
    |
    */

    'notifications' => [
        /*
        |--------------------------------------------------------------------------
        | General Notifications Settings
        |--------------------------------------------------------------------------
        */

        // Type environments names to enable notifications on.
        'notification_enabled_on' => ['production', 'live'],

        // Define default notification channel
        // Possible Value: log, mail, slack, pushover
        'notification_channel' => 'mail',

        // notification title/subject
        'notification_title' => 'Server Monitor Alert',

        /*
        |--------------------------------------------------------------------------
        | Mail Channel Settings
        |--------------------------------------------------------------------------
        */

        // Define default email(s) that will receive notification when a check fails.
        'notification_notify_emails' => [
            'foo@example.com',
        ],

        // Define mail channel "from". Leave empty to use default.
        'notification_mail_from' => '',

        /*
        |--------------------------------------------------------------------------
        | Slack Channel Settings
        |--------------------------------------------------------------------------
        */

        'notification_slack_hook_url' => 'https://hooks.slack.com/...', // see: https://api.slack.com/incoming-webhooks
        'notification_slack_channel' => '#myapp',
        'notification_slack_icon' => ':robot:',
        'notification_slack_color' => 'danger',

        /*
        |--------------------------------------------------------------------------
        | Pushover Channel Settings
        |--------------------------------------------------------------------------
        */

        'notification_pushover_token' => '',
        'notification_pushover_user_key' => '',
        'notification_pushover_sound' => 'siren',
    ],

];
