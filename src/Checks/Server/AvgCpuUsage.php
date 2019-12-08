<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Server;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class AvgCpuUsage implements Check
{
    private $percent;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->percent = round($this->getCPUUsagePercentage(), 2);

        $failPercentage = $config ['fail_percentage'];

        return !($this->percent >= $failPercentage);
    }

    protected function getCPUUsagePercentage()
    {
        $cpu = shell_exec("grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'");

        return (float)$cpu;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Average CPU usage at: ' . $this->percent . '%';
    }
}
