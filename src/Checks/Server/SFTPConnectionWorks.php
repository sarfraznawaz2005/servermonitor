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
use League\Flysystem\Sftp\SftpAdapter;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class SFTPConnectionWorks implements Check
{
    private $servers;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'SFTP Connection Works';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->servers = Collection::make(Arr::get($config, 'servers', []));

        $this->servers = $this->servers->reject(static function ($options) {
            try {
                $adapter = new SftpAdapter($options);
                $adapter->getConnection();

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
        return "SFTP connection failed for servers:\n" . $this->servers->keys()->implode(PHP_EOL);
    }
}
