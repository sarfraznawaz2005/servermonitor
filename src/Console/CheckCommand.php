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

        $count = 0;
        foreach ($results as $type => $checks) {
            $this->comment(strtoupper($type));

            foreach ($checks as $check) {
                $count++;

                $name = $check['name'];
                $message = $check['message'];

                $count = str_pad($count, 2, '0', STR_PAD_LEFT);

                if ($check['result']) {
                    $text = "$count: <fg=green>PASS --> $name</fg=green>";
                } else {
                    $text = "$count: <fg=red>FAIL --> $name ($message)</fg=red>";
                }

                $this->line($text);
            }
        }

    }
}
