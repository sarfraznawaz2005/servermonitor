<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Illuminate\Support\Facades\Artisan;
use Sarfraznawaz2005\ServerMonitor\Contract\Check;

class MigrationsAreUpToDate implements Check
{
    private $error = null;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Migrations are up to date';
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
            Artisan::call('migrate', ['--pretend' => 'true', '--force' => 'true']);
            $output = Artisan::output();

            return strstr($output, 'Nothing to migrate.');
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
        if ($this->error !== null) {
            return 'Unable to check for migrations: ' . $this->error;
        }

        return 'Pending migrations. Call "php artisan migrate" to update database.';
    }
}
