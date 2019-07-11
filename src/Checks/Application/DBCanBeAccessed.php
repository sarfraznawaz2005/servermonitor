<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Sarfraznawaz2005\ServerMonitor\Contract\Check;

class DBCanBeAccessed implements Check
{
    private $error;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Database can be accessed';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        try {
            DB::connection(config('database.default'))->getPdo();

            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return false;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The database can not be accessed: ' . $this->error;
    }
}
