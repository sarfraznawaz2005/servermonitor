<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 *
 * @via BeyondCode\SelfDiagnosis
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class SupervisorProgramsAreRunning implements Check
{
    protected $notRunningPrograms;
    protected $error;
    protected $systemFunctions;

    const REGEX_SUPERVISORCTL_STATUS = '/^(\S+)\s+RUNNING\s+pid\s+(\d+),\s+uptime\s+(\d+):(\d+):(\d+)$/';

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'All supervisor programs are running';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->notRunningPrograms = new Collection(Arr::get($config, 'programs', []));

        if ($this->notRunningPrograms->isEmpty()) {
            return true;
        }

        if (!$this->isFunctionAvailable('shell_exec')) {
            $this->error = "The function 'shell_exec' is not defined or disabled, so we cannot check the running programs.";
            return false;
        }

        if ($this->isWindowsOperatingSystem()) {
            $this->error = 'This check cannot be run on Windows.';
            return false;
        }

        $programs = $this->callShellExec('supervisorctl status');

        if ($programs === null || $programs === '') {
            $this->error = "The 'supervisorctl' command is not available on the current OS.";
            return false;
        }

        $restartedWithin = Arr::get($config, 'restarted_within', 0);
        $programs = explode("\n", $programs);

        foreach ($programs as $program) {
            /*
             * Capture groups of regex:
             * (program name) (process id) (uptime hours) (minutes) (seconds)
             */
            $isMatch = preg_match(self::REGEX_SUPERVISORCTL_STATUS, trim($program), $matches);

            if ($isMatch) {
                if ($restartedWithin > 0) {
                    $totalSeconds = $matches[3] * 3600 + $matches[4] * 60 + $matches[5];

                    if ($totalSeconds > $restartedWithin) {
                        continue;
                    }
                }

                $this->notRunningPrograms = $this->notRunningPrograms->reject(function ($item) use ($matches) {
                    return $item === $matches[1];
                });
            }
        }

        return $this->notRunningPrograms->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        if ($this->error) {
            return $this->error;
        }

        return "The following programs are not running or require a restart:\n" . $this->notRunningPrograms->implode(PHP_EOL);
    }

    /**
     * Performs a shell_exec call. Acts as proxy.
     *
     * @param string $command
     * @return null|string
     */
    public function callShellExec(string $command)
    {
        return shell_exec($command);
    }

    /**
     * Checks if a function is defined and not disabled.
     *
     * @param string $function
     * @return bool
     */
    public function isFunctionAvailable(string $function): bool
    {
        return is_callable($function) && false === stripos(ini_get('disable_functions'), $function);
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
