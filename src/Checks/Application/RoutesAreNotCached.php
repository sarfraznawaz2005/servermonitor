<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Sarfraznawaz2005\ServerMonitor\Contract\Check;

class RoutesAreNotCached implements Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Routes Cache';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        return app()->routesAreCached() === false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The routes should not be cached during development. Call "php artisan route:clear" to clear the route cache.';
    }
}
