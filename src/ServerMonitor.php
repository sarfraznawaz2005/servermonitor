<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/11/2019
 * Time: 1:38 PM
 */

namespace Sarfraznawaz2005\ServerMonitor;


use Carbon\Carbon;

class ServerMonitor
{
    public $cacheFile;

    public function __construct()
    {
        $this->cacheFile = storage_path('sm_checks.cache');
    }

    /**
     * Checks if current application environment is production.
     *
     * @return bool
     */
    protected function isProduction(): bool
    {
        $envs = config('server-monitor.production_environments');

        return in_array(config('app.env'), $envs, true);
    }

    /**
     * Returns all checks that need to be run.
     *
     * @return array
     */
    public function getCheckClasses(): array
    {
        $key = 'application.' . config('app.env') . '.checks';
        $env = $this->isProduction() ? 'production' : 'development';

        $serverChecks['server.checks'] = config('server-monitor.checks.server');
        $commonChecks['application.common.checks'] = config('server-monitor.checks.application.common');
        $envChecks[$key] = config("server-monitor.checks.application.$env");

        return array_filter(array_merge($serverChecks, $commonChecks, $envChecks));
    }

    /**
     * Runs all checks and returns results.
     *
     * @return array
     */
    public function runChecks(): array
    {
        $results = [];

        $checksAll = $this->getCheckClasses();

        foreach ($checksAll as $type => $checks) {
            if ($checks) {
                foreach ($checks as $check => $config) {

                    if (!is_array($config)) {
                        $check = $config;
                        $config = [];
                    }

                    $object = app()->make($check);
                    $result = $object->check($config);
                    $name = $object->name();
                    $error = $object->message();

                    if (!app()->runningInConsole()) {
                        $error = nl2br($error);
                    }

                    $results[] = [
                        'type' => $type,
                        'checker' => $check,
                        'name' => $name,
                        'status' => $result,
                        'error' => $error,
                    ];
                }
            }
        }

        $results = collect($results)->groupBy('type')->toArray();

        @file_put_contents($this->cacheFile, serialize($results));

        return $results;
    }

    /**
     * Returns check results from cache file or optionally run and return new check results
     *
     * @param bool $refresh
     * @return array
     */
    public function getChecks($refresh = false): array
    {
        if ($refresh) {
            $this->runChecks();
        } elseif (!file_exists($this->cacheFile)) {
            return [];
        }

        return unserialize(file_get_contents($this->cacheFile));
    }

    /**
     * Returns last check-run time
     *
     * @return string
     */
    public function getLastCheckedTime(): string
    {
        if (!file_exists($this->cacheFile)) {
            return 'N/A';
        }

        return Carbon::parse(date('F d Y H:i:s.', filemtime($this->cacheFile)))->diffForHumans();
    }
}
