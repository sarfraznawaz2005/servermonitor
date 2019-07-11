<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 4:50 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks;


abstract class BaseCheck
{
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
    public function getChecks(): array
    {
        $serverChecks['server.checks'] = config('server-monitor.checks.server');
        $applicationCommonChecks['application.common.checks'] = config('server-monitor.checks.application.common');

        $key = 'application.' . config('app.env') . '.checks';

        if ($this->isProduction()) {
            $envChecks[$key] = config('server-monitor.checks.application.production');
        } else {
            $envChecks[$key] = config('server-monitor.checks.application.development');
        }

        $checks = array_merge($serverChecks, $applicationCommonChecks, $envChecks);

        return array_filter($checks);
    }
}
