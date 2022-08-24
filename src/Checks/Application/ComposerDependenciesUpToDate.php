<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class ComposerDependenciesUpToDate implements Check
{
    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $binary = $config['binary_path'];

        chdir(base_path());
        exec("$binary install --dry-run --no-interaction 2>&1", $output, $status);
        $output = implode('-', $output);

        return strstr($output, 'Nothing to install');
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "The composer dependencies are not up to date.\nCall 'composer install' to update them.";
    }
}
