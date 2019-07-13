<?php

namespace Sarfraznawaz2005\ServerMonitor\Console;

use Sarfraznawaz2005\ServerMonitor\ServerMonitor;

class CheckCommand extends BaseCommand
{
    protected $signature = 'servermonitor:check {checker? : Optional check to run.}';
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

        if ($check = trim($this->argument('checker'))) {
            $results = $sm->runCheck($check);
            $this->outputResult($results);
        } else {
            $results = $sm->runChecks();
            $this->outputResults($results);
        }
    }
}
