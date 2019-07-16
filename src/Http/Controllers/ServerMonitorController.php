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
        if (!config('server-monitor.enabled')) {
            exit('Server Monitor is disabled!');
        }

        if (!config('server-monitor.web_interface_enabled')) {
            abort(404);
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

    /**
     * Runs given single check.
     */
    public function refresh(): array
    {
        return $this->serverMonitor->runCheck(request()->check);
    }
}
