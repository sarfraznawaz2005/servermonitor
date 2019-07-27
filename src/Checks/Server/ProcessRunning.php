<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 *
 * @via BeyondCode\SelfDiagnosis
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Server;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use JJG\Ping;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class ProcessRunning implements Check
{
    protected $processes;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        if ($this->isWindowsOperatingSystem()) {
            return false;
        }

        $this->processes = Collection::make(Arr::get($config, 'processes', []));

        $this->processes = $this->processes->reject(function ($process) {
            return $this->processRunning($process);
        });

        return $this->processes->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        if ($this->isWindowsOperatingSystem()) {
            return 'Check not available on Windows OS.';
        }

        $NL = app()->runningInConsole() ? "\n" : '<br>';

        return "The following processes are not running:$NL" . $this->processes->implode($NL);
    }

    protected function processRunning($processName)
    {
        exec("pgrep $processName", $pids);

        if (count($pids)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if we are running on a windows operating system.
     *
     * @return bool
     */
    public function isWindowsOperatingSystem(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
