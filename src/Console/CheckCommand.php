<?php

namespace Sarfraznawaz2005\ServerMonitor\Console;

use Illuminate\Console\Command;
use Sarfraznawaz2005\ServerMonitor\ServerMonitor;

class CheckCommand extends Command
{
    protected $name = 'servermonitor:check';
    protected $description = 'Checks status of all server and application checks.';

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

        $sm = app()->make(ServerMonitor::class);
        $results = $sm->runChecks();

        $data = [];
        foreach ($results as $type => $checks) {
            foreach ($checks as $check) {
                $name = $check['name'];
                $message = $check['message'];

                if ($check['result']) {
                    $message = '';
                    $result = '<fg=green>PASSED</fg=green>';
                } else {
                    $result = '<fg=red>FAILED</fg=red>';
                }

                $data[] = [$type, $name, $result, $message];
            }
        }

        if (!app()->runningInConsole()) {
            echo '<pre>';
        }

        $headers = ['Check Type', 'Check Name', 'Result', 'Error'];

        $this->table($headers, $data);
    }
}
