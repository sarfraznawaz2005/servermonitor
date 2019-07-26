<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class CheckPhpIniValues implements Check
{
    private $values = [];

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $values = parse_ini_file(php_ini_loaded_file());

        if (!empty($config['checks']) && is_array($config['checks'])) {
            foreach ($config['checks'] as $key => $value) {
                if (isset($values[$key])) {
                    if ($value != $values[$key]) {
                        $this->values[$key] = $values[$key];
                    }
                }
            }
        }

        return collect($this->values)->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "The following values don't match:\n" . collect($this->values)->keys()->implode(PHP_EOL);
    }
}
