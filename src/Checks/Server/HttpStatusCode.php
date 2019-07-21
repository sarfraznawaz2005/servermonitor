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

class HttpStatusCode implements Check
{
    private $sites;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->sites = Collection::make(Arr::get($config, 'sites', []));

        $this->sites = $this->sites->reject(static function ($site) {
            try {

                $headers = get_headers($site['url']);

                preg_match('/HTTP\/.* ([0-9]+) .*/', $headers[0], $status);

                return ($status[1] == $site['expected_code']);

            } catch (\Exception $e) {
                return false;
            }
        });

        return $this->sites->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "Intended HTTP status code failed for :\n" . $this->sites->keys()->implode(PHP_EOL);
    }
}
