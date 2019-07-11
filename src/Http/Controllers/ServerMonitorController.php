<?php

namespace Sarfraznawaz2005\ServerMonitor\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Sarfraznawaz2005\ServerMonitor\ServerMonitor;

class ServerMonitorController extends BaseController
{
    public function __construct()
    {
        if (config('server-monitor.http_authentication')) {
            $this->middleware('auth.basic');
        }
    }

    public function index()
    {
        $title = 'Server Monitor Checks Status';

        $sm = app()->make(ServerMonitor::class);
        $results = $sm->runChecks();

        return view('servermonitor::index', compact('title', 'results'));
    }
}
