<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Sarfraznawaz2005\ServerMonitor\Contract\Check;

class AppKeySet implements Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'APP_KEY is set';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        return config('app.key') !== null;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The APP_KEY is not set. Call "php artisan key:generate" to set one.';
    }
}
