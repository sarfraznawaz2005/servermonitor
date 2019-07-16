[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

# Laravel Server Monitor

Laravel package to periodically monitor the health of your server and application. It ships with common checks out of the box and allows you to add your own custom checks too. 

The packages comes with both console and web interfaces, here are screenshots:


![Screen 1](https://github.com/sarfraznawaz2005/servermonitor/blob/master/screen1.gif?raw=true)

![Screen 2](https://github.com/sarfraznawaz2005/servermonitor/blob/master/screen2.gif?raw=true)

![Screen 3](https://github.com/sarfraznawaz2005/servermonitor/blob/master/screen3.gif?raw=true)

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

See `config/server-monitor.php` config file to customize route and more settings.

## Built-in Checks

The package comes with following checks out of the box. Note that checks can be divided into three categories:

 - **Server Checks:** Checks that are related to your server only.
 - **Common Checks:** Checks that are related to your application only but are common in nature irrespective of which environment your application is running on. These checks run on all environments.
 - **Environment Checks:** Checks that are related to your application only but are limited to specific environment such as production or development.




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