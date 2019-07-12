<?php

namespace Sarfraznawaz2005\ServerMonitor\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Sarfraznawaz2005\ServerMonitor\ServerMonitor;

class ServerMonitorController extends BaseController
{
    protected $serverMonitor;

    /**
     * ServerMonitorController constructor.
     * @param ServerMonitor $serverMonitor
     */
    public function __construct(ServerMonitor $serverMonitor)
    {
        if (config('server-monitor.http_authentication')) {
            $this->middleware('auth.basic');
        }

        $this->serverMonitor = $serverMonitor;
    }

    /**
     * Lists status of all checks.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'ServerMonitor';

        $checkResults = $this->serverMonitor->getChecks();
        $lastRun = $this->serverMonitor->getLastCheckedTime();

        return view('servermonitor::index', compact('title', 'checkResults', 'lastRun'));
    }

    /**
     * Runs checks for all services.
     */
    public function refreshAll(): array
    {
        return $this->serverMonitor->runChecks();
    }
}
