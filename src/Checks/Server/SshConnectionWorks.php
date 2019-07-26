<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Server;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;
use Symfony\Component\Process\Process;

class SshConnectionWorks implements Check
{
    private $servers;
    private $error;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        set_time_limit(0);

        $this->servers = Collection::make(Arr::get($config, 'servers', []));

        $this->servers = $this->servers->reject(function ($options) {

            try {

                $port = '-p 22';
                $key = '';

                if (isset($options['port']) && $options['port']) {
                    $port = "-p {$options['port']}";
                }

                if (isset($options['privateKey']) && $options['privateKey']) {
                    $key = "-i {$options['privateKey']}";
                }

                $command = "ssh $port $key {$options['username']}@{$options['host']}";
                $process = new Process($command);
                $process->setTimeout(null);
                $process->run();

                if (!$process->isSuccessful()) {
                    $this->error = 'Could not run command: ' . $command;
                }

                $process->run(function ($type, $buffer) {
                    if (Process::ERR === $type) {
                        $this->error .= $buffer . "\n";
                    }
                });

                if (!str_contains($process->getOutput(), 'login')) {
                    return false;
                }

                return true;
            } catch (\Exception $e) {
                return false;
            }
        });

        return $this->servers->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        $NL = app()->runningInConsole() ? "\n" : '<br>';

        if ($this->error) {
            return $this->error;
        }

        return "SSH connection failed for servers:$NL" . $this->servers->keys()->implode($NL);
    }
}
