<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable / Disable
    |--------------------------------------------------------------------------
    |
    | Enable or disable Server Monitor
    |
    */
    'enabled' => env('ENABLE_SERVER_MONITOR', true),

    /*
    |--------------------------------------------------------------------------
    | Server Monitor Web Route
    |--------------------------------------------------------------------------
    |
    | Define if web interface will be enabled and route where Server Monitor
    | will be available in your app.
    |
    */
    'web_interface_enabled' => true,
    'route' => 'servermonitor',

    /*
    |--------------------------------------------------------------------------
    | Basic Http Authentication
    |--------------------------------------------------------------------------
    |
    | If "true", the Server Monitor page can be viewed by any user who provides
    | correct login information (eg all app users).
    |
    */
    'http_authentication' => false,

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
    | Define checks for server and application that will be performed
    |
    */
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
