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
        $this->cacheFile = storage_path('servermonitor.cache');
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
        $key = 'application.' . config('app.env');
        $env = $this->isProduction() ? 'production' : 'development';

        $serverChecks['server'] = config('server-monitor.checks.server');
        $commonChecks['application.common'] = config('server-monitor.checks.application.common');
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

        $totalChecksCount = 0;
        $passedChecksCount = 0;
        foreach ($checksAll as $type => $checks) {
            if ($checks) {
                foreach ($checks as $check => $config) {
                    $totalChecksCount++;

                    if (!is_array($config)) {
                        $check = $config;
                        $config = [];
                    }

                    $sTime = microtime(true);
                    $object = app()->make($check);

                    try {
                        $status = $object->check($config);
                        $error = $object->message();
                    } catch (\Exception $e) {
                        $status = false;
                        $error = $object->message();
                    }

                    $eTime = number_format((microtime(true) - $sTime) * 1000, 2);

                    if ($status) {
                        $passedChecksCount++;
                    } else {
                        Notifier::notify($object, $config);
                    }

                    $results[] = [
                        'type' => $type,
                        'checker' => $this->getClassName($check),
                        'name' => $config['name'] ?? $this->normalizeName($this->getClassName($check)),
                        'status' => $status,
                        'error' => $error,
                        'time' => sprintf("%dms", $eTime),
                    ];
                }
            }
        }

        $results = collect($results)->groupBy('type')->toArray();

        $results['counts'] = [
            'total_checks_count' => $totalChecksCount,
            'passed_checks_count' => $passedChecksCount,
            'failed_checks_count' => $totalChecksCount - $passedChecksCount,
        ];

        @file_put_contents($this->cacheFile, serialize($results));

        return $results;
    }

    /**
     * Runs given single check and returns result.
     *
     * @param $checkClass
     * @return array
     */
    public function runCheck($checkClass): array
    {
        $checksAll = $this->getCheckClasses();

        foreach ($checksAll as $type => $checks) {
            if ($checks) {
                foreach ($checks as $check => $config) {

                    if (!is_array($config)) {
                        $check = $config;
                        $config = [];
                    }

                    if ($checkClass === $this->getClassName($check)) {
                        $sTime = microtime(true);
                        $object = app()->make($check);
                        $status = $object->check($config);
                        $error = $object->message();
                        $eTime = number_format((microtime(true) - $sTime) * 1000, 2);

                        return [
                            'type' => $type,
                            'checker' => $check,
                            'name' => $config['name'] ?? $this->normalizeName($this->getClassName($check)),
                            'status' => $status,
                            'error' => $error,
                            'time' => sprintf("%dms", $eTime),
                        ];
                    }
                }
            }
        }

        throw new \InvalidArgumentException("$checkClass not found!");
    }

    /**
     * Returns check results from cache file or optionally run and return new check results
     *
     * @return array
     */
    public function getChecks(): array
    {
        if (!file_exists($this->cacheFile)) {
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

    /**
     * Returns class name from FQN
     *
     * @param $namespace
     * @return mixed
     */
    public function getClassName($namespace)
    {
        $path = explode('\\', $namespace);

        return array_pop($path);
    }

    /**
     * Converts "PascalCase" to "Pascal Case"
     *
     * @param $name
     * @return string
     */
    protected function normalizeName($name): string
    {
        return ucwords(strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $name)));
    }
}
