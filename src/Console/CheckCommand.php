<?php

namespace Sarfraznawaz2005\ServerMonitor\Console;

use Illuminate\Console\Command;
use Sarfraznawaz2005\ServerMonitor\Checks\Application\EnvFileExists;

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
        }

        $object = app()->make(EnvFileExists::class);
        $checksAll = $object->getChecks();

        $count = 0;
        foreach ($checksAll as $type => $checks) {
            if ($checks) {
                $this->comment(strtoupper($type));

                foreach ($checks as $check => $config) {
                    $count++;

                    if (!is_array($config)) {
                        $check = $config;
                        $config = [];
                    }

                    $class = app()->make($check);
                    $name = $class->name();
                    $message = $class->message();

                    if ($class->check($config)) {
                        $text = "$count: <fg=green>PASS --> $name</fg=green>";
                    } else {
                        $text = "$count: <fg=red>FAIL --> $name ($message)</fg=red>";
                    }

                    $this->line($text);
                }
            }
        }
    }
}
