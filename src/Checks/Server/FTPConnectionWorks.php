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

class FTPConnectionWorks implements Check
{
    private $error;
    private $servers;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'FTP Connection Works';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        try {

            $this->servers = Collection::make(Arr::get($config, 'servers', []));

            $this->servers = $this->servers->reject(function ($array) {

                $con = ftp_connect($array['host'], $array['port'] ?? null, $array['timeout'] ?? null);

                if (!$con) {
                    return false;
                }

                $loggedIn = ftp_login($con, $array['username'], $array['password']);

                if (!$loggedIn) {
                    return false;
                }

                ftp_close($con);

                return true;
            });

            return $this->servers->isEmpty();

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
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

        return "FTP connection failed for servers:\n" . $this->servers->keys()->implode(PHP_EOL);
    }
}
