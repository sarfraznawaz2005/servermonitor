[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

# Laravel Server Monitor

Laravel package to periodically monitor the health of your server and application. It ships with common checks out of the box and allows you to add your own custom checks too. The packages comes with both console and web interfaces.

## Requirements

 - PHP >= 7+
 - Laravel 5+

## Installation

``` bash
$ composer require sarfraznawaz2005/servermonitor
```

Additional step for Laravel < 5.5:

Add Service Provider to `config/app.php` in `providers` section
```php
Sarfraznawaz2005\ServerMonitor\ServiceProvider::class,
```

---

Now publish package's config file by running below command:

```bash
$ php artisan vendor:publish --provider="Sarfraznawaz2005\ServerMonitor\ServiceProvider"
```

See `config/server-monitor.php` config file to customize checks, notifications and more.

## Built-in Checks

The package comes with following checks out of the box. Checks can be divided into three categories:

 - **Server Checks:** Checks that are related to your server only.
 - **Common Checks:** Checks that are related to your application only but are common in nature irrespective of which environment your application is running on. These checks run on all environments.
 - **Environment Checks:** Checks that are related to your application only but are limited to specific environment such as production or development.

**Server Checks**

 - :white_check_mark:  Disk Space Enough
 - :white_check_mark:  FTP Connection Works
 - :white_check_mark:  SFTP Connection Works
 - :white_check_mark:  SSL Certificate Valid
 - :white_check_mark:  SSH Connection Works

**Common Checks**

 - :white_check_mark:  Required PHP extensions are installed
 - :white_check_mark:  Correct PHP version installed
 - :white_check_mark:  The environment file exists
 - :white_check_mark:  APP_KEY is set
 - :white_check_mark:  Correct Directory Permissions
 - :white_check_mark:  Database can be accessed
 - :white_check_mark:  Migrations are up to date
 - :white_check_mark:  Composer dependencies up to date
 - :white_check_mark:  Check Packages Security
 - :white_check_mark:  Storage directory is linked
 - :white_check_mark:  The Redis cache can be accessed
 - :white_check_mark:  Mail is Working

**Environment Checks (Development)**

 - :white_check_mark:  Debug Mode ON
 - :white_check_mark:  Config Cache OFF
 - :white_check_mark:  Routes Cache OFF

**Environment Checks (Production)**

 - :white_check_mark:  Debug Mode OFF
 - :white_check_mark:  Config Cache ON
 - :white_check_mark:  Routes Cache ON
 - :white_check_mark:  Unwanted PHP extensions disabled
 - :white_check_mark:  Are certain servers pingable
 - :white_check_mark:  Supervisor programs are running

See `config/server-monitor.php` file for all checks. Note that some checks are commented intentionally, you can un-comment them if you need to use them.


## Commands

The package comes with two commands:

 - `php artisan servermonitor:check` Runs all checks enabled in config file and return their new status.
 - `php artisan servermonitor:status` Returns previously-run status of all checks without running new process.

Here is how it looks:

![Screen 3](https://github.com/sarfraznawaz2005/servermonitor/blob/master/screen3.gif?raw=true)


Both commands take optional argument. If specified, it will run check or return status of only specified check:

 - `php artisan servermonitor:check AppKeySet` Runs new check process for check `AppKeySet`
 - `php artisan servermonitor:status AppKeySet` Returns previous run status for check `AppKeySet`


## Scheduling

You can use `servermonitor:check` command to check status of enabled checks periodically instead of running this command manually each time.

Schedule it in Laravel's console kernel file accordingly:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
   $schedule->command('servermonitor:check')->hourly();
}
```

## Web Interface

The package provides built-in web interface. You can customize the route of web interface in config file `'route' => 'servermonitor'`. Once done, you can visit Web Interface at url `http://yourapp.com/servermonitor`. Replace `servermonitor` with route you used.

Other than commands, you can also use Web Interface to run new checks process for all or individual checks.

![Screen 1](https://github.com/sarfraznawaz2005/servermonitor/blob/master/screen1.gif?raw=true)

![Screen 2](https://github.com/sarfraznawaz2005/servermonitor/blob/master/screen2.gif?raw=true)

**Disabling Web Interface**

If you would like to disable Web Interface, you can set `web_interface_enabled` to `false` and now hitting web interface route would result in 404.

**Running/Getting Checks Programmatically**

If you still would like to show status of various checks in your view in your own way, you can get status of all checks programmatically like so:

```php
use Sarfraznawaz2005\ServerMonitor\ServerMonitor;

$sm = new ServerMonitor();
$checkResults = $sm->getChecks();
dump($checkResults);
```

You can also run check(s) programmatically (`$sm->runChecks()`), see available methods in file: `vendor/Sarfraznawaz2005/ServerMonitor/src/ServerMonitor.php`

## Alert Configuration

You can get notified ***when a check fails***. Package supports these alert/notification channels: 

 - `mail`
 - `log`
 - `slack`
 - `pushover`

Update your notification options under `notifications` option in config file.

Note that you can also customize all notification options for individual checks too. Let's say you have specified `mail` as default channel for your alerts but for following check only, it will be alerted via `log` channel and a different alert title:

````php
\Sarfraznawaz2005\ServerMonitor\Checks\Application\AppKeySet::class => [
    'notification_channel' => 'log',
    'notification_title' => 'Hello World'
]
````

You can also disable alerts for individual checks like so:

````php
\Sarfraznawaz2005\ServerMonitor\Checks\Application\AppKeySet::class => [
    'disable_notification' => true
]
````

## Creating Your Own Custom Checks

You can create custom checks, by implementing the [`Sarfraznawaz2005\ServerMonitor\Checks\Check`] interface and adding the class to the config file. Example:

````php
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class MyCheck implements Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'My Custom Check';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        return 1 === 1;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "This error message that users see if check returns false.";
    }
}
````


## Credits

- [Sarfraz Ahmed][link-author]
- [All Contributors][link-contributors]

## License

Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sarfraznawaz2005/servermonitor.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sarfraznawaz2005/servermonitor.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/sarfraznawaz2005/servermonitor
[link-downloads]: https://packagist.org/packages/sarfraznawaz2005/servermonitor
[link-author]: https://github.com/sarfraznawaz2005
[link-contributors]: https://github.com/sarfraznawaz2005/servermonitor/graphs/contributors