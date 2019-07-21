<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class RoutesAreCached implements Check
{
    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        return app()->routesAreCached() === true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "The routes should be cached in production for performance.\nCall 'php artisan route:cache' to create the route cache.";
    }
}
