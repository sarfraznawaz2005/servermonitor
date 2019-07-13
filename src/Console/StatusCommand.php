<?php

namespace Sarfraznawaz2005\ServerMonitor\Console;

use Sarfraznawaz2005\ServerMonitor\ServerMonitor;

class StatusCommand extends BaseCommand
{
    protected $signature = 'servermonitor:status {checker? : Optional check to see status for.}';
    protected $description = 'Checks status of server & application checks without running new checks process.';

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
            $results = $sm->getChecks();

            // load from cache or run new checks
            if (!file_exists($sm->cacheFile)) {
                $this->warn('No checks run previously. Please run "servermonitor:check" command to run checks first.');
                return false;
            }

            $this->outputResults($results);
        }
    }
}
