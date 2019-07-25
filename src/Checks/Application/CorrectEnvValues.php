<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class CorrectEnvValues implements Check
{
    private $errors;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $checks = $config['checks'];

        foreach ($checks as $type => $check) {
            $path = $check['path'];
            $values = $check['expected_values'];

            foreach ($values as $valueKey => $value) {
                $actualValues = @include $path;

                if ($actualValues && is_array($actualValues)) {
                    if ($value !== $actualValues[$valueKey]) {
                        $this->errors .= "$type:$valueKey: '$value' <> '$actualValues[$valueKey]'" . PHP_EOL;
                    }
                }
            }
        }

        return $this->errors === null;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "The following values don't match:\n" . $this->errors;
    }
}
