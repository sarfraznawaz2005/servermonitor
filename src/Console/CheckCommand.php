<?php

namespace Sarfraznawaz2005\ServerMonitor\Console;

use Sarfraznawaz2005\ServerMonitor\ServerMonitor;

class CheckCommand extends BaseCommand
{
    protected $name = 'servermonitor:check';
    protected $description = 'Starts new checks process for server and application.';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        if (!config('server-monitor.enabled')) {
            $this->warn('Server Monitor is disabled!');
            return false;
        }

        $sm = new ServerMonitor();
        $results = $sm->runChecks();

        $this->outputResults($results);
    }
}
