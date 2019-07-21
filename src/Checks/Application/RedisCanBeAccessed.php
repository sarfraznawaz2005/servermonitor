<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class RedisCanBeAccessed implements Check
{
    private $error;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        try {
            if (Arr::get($config, 'default_connection', true)) {
                if (!$this->testConnection()) {
                    $this->error = 'The default cache is not reachable.';

                    return false;
                }
            }

            foreach (Arr::get($config, 'connections', []) as $connection) {
                if (!$this->testConnection($connection)) {
                    $this->error = "The named cache $connection is not reachable.";

                    return false;
                }
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();

            return false;
        }

        return true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "The Redis cache can not be accessed:\n" . $this->error;
    }

    /**
     * Tests a redis connection and returns whether the connection is opened or not.
     *
     * @param string|null $name
     * @return bool
     */
    private function testConnection(string $name = null): bool
    {
        $connection = Redis::connection($name);
        $connection->connect();

        return $connection->isConnected();
    }
}
