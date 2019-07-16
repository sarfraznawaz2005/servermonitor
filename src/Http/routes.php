<?php

Route::group(
    [
        'namespace' => 'Sarfraznawaz2005\ServerMonitor\Http\Controllers',
        'prefix' => config('server-monitor.route', 'servermonitor')
    ],
    static function () {

        // list checks
        Route::group(['middleware' => 'auth.basic_servermonitor'], static function () {
            Route::get('/', 'ServerMonitorController@index');
        });

        // refresh all checks
        Route::get('refresh', 'ServerMonitorController@refresh')->name('servermonitor_refresh');

        // refresh single check
        Route::get('refresh_all', 'ServerMonitorController@refreshAll')->name('servermonitor_refresh_all');
    }
);
