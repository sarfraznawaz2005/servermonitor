<?php

Route::group(
    [
        'namespace' => 'Sarfraznawaz2005\ServerMonitor\Http\Controllers',
        'prefix' => config('server-monitor.route', 'servermonitor')
    ],
    static function () {
        // list checks
        Route::get('/', 'ServerMonitorController@index')->name('servermonitor_home');
    }
);
