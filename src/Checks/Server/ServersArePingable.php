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

class ServersArePingable implements Check
{
    protected $servers;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Servers are pingable';
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

        $this->servers = $this->servers->reject(static function ($server) {

            $ping = new Ping($server['host']);
            $ping->setPort($server['port']);
            $ping->setTimeout($server['timeout']);

            if ($server['port'] === null) {
                $latency = $ping->ping('exec');
            } else {
                $latency = $ping->ping('fsockopen');
            }

            return $latency !== false;
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
        return $this->servers->map(static function ($server) {

            $host = $server['host'];
            $port = $server['port'] ?? 'n/a';
            $timeout = $server['timeout'];

            return "Server $host:$port is not reachable (timeout {$timeout}s).";
        })->implode(PHP_EOL);
    }
}
