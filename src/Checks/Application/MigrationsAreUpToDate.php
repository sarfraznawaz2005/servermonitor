<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class MigrationsAreUpToDate implements Check
{
    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        Artisan::call('migrate:status');
        $output = Artisan::output();

        if (Str::contains(trim($output), 'No migrations')) {
            return true;
        }

        $output = collect(explode("\n", $output));
        $output = $output->reject(function ($item) {
            return !Str::contains($item, '| N');
        });

        $count = $output->count() !== 0;

        if ($count) {
            return false;
        }

        return true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "Pending migrations. Call 'php artisan migrate' to update database.";
    }
}
