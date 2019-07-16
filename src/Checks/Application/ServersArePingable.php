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
use JJG\Ping;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;
use Sarfraznawaz2005\ServerMonitor\Server;

class ServersArePingable implements Check
{
    const DEFAULT_TIMEOUT = 5;

    protected $notReachableServers;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Required servers are pingable';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->notReachableServers = $this->parseConfiguredServers(Arr::get($config, 'servers', []));

        if ($this->notReachableServers->isEmpty()) {
            return true;
        }

        $this->notReachableServers = $this->notReachableServers->reject(static function (Server $server) {
            $ping = new Ping($server->getHost());
            $ping->setPort($server->getPort());
            $ping->setTimeout($server->getTimeout());

            if ($ping->getPort() === null) {
                $latency = $ping->ping('exec');
            } else {
                $latency = $ping->ping('fsockopen');
            }

            return $latency !== false;
        });

        return $this->notReachableServers->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->notReachableServers->map(static function (Server $server) {

            $host = $server->getHost();
            $port = $server->getPort() ?? 'n/a';
            $timeout = $server->getTimeout();

            return "The server '$host' (port: $port) is not reachable (timeout after $timeout seconds).";
        })->implode(PHP_EOL);


    }

    /**
     * Parses an array of servers which can be given in different formats.
     * Unifies the format for the resulting collection.
     *
     * @param array $servers
     * @return Collection
     */
    private function parseConfiguredServers(array $servers): Collection
    {
        $result = new Collection();

        foreach ($servers as $server) {
            if (is_array($server)) {

                if (!empty(array_except($server, ['host', 'port', 'timeout']))) {
                    throw new \InvalidArgumentException('Servers in array notation may only contain a host, port and timeout parameter.');
                }

                if (!array_has($server, 'host')) {
                    throw new \InvalidArgumentException('For servers in array notation, the host parameter is required.');
                }

                $host = Arr::get($server, 'host');
                $port = Arr::get($server, 'port');
                $timeout = Arr::get($server, 'timeout', self::DEFAULT_TIMEOUT);

                $result->push(new Server($host, $port, $timeout));
            } else {
                if (is_string($server)) {
                    $result->push(new Server($server, null, self::DEFAULT_TIMEOUT));
                } else {
                    throw new \InvalidArgumentException('The server configuration may only contain arrays or strings.');
                }
            }
        }

        return $result;
    }
}
