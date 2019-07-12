<?php

Route::group(
    [
        'namespace' => 'Sarfraznawaz2005\ServerMonitor\Http\Controllers',
        'prefix' => config('server-monitor.route', 'servermonitor')
    ],
    static function () {
        // list checks
        Route::get('/', 'ServerMonitorController@index');
        Route::get('refresh_all', 'ServerMonitorController@refreshAll')->name('servermonitor_refresh_all');
        Route::get('refresh/{name}', 'ServerMonitorController@refresh');
    }
);
