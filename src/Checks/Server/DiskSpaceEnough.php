<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Server;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class DiskSpaceEnough implements Check
{
    private $error;
    private $percent;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $totalSpace = disk_total_space(base_path());
        $freeSpace = disk_free_space(base_path());
        $usedSpace = $totalSpace - $freeSpace;

        $this->percent = round(($usedSpace / $totalSpace) * 100);

        $failPercentage = $config ['fail_percentage'];

        return !($this->percent >= $failPercentage);
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

        return 'Disk Space usage at: ' . $this->percent . '%';
    }
}
