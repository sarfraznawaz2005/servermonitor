<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

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
                $actualValues = include $path;

                if ($actualValues && is_array($actualValues)) {
                    $actualValue = $this->getValueByKey($valueKey, $actualValues);

                    if ($value !== $actualValue) {
                        $this->errors .= "$type : $valueKey" . PHP_EOL;
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

    protected function getValueByKey($key, array $data, $default = null)
    {
        // @assert $key is a non-empty string
        // @assert $data is a loopable array
        // @otherwise return $default value
        if (!is_string($key) || empty($key) || !count($data)) {
            return $default;
        }

        // @assert $key contains a dot notated string
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);

            foreach ($keys as $innerKey) {
                // @assert $data[$innerKey] is available to continue
                // @otherwise return $default value
                if (!array_key_exists($innerKey, $data)) {
                    return $default;
                }

                $data = $data[$innerKey];
            }

            return $data;
        }

        // @fallback returning value of $key in $data or $default value
        return array_key_exists($key, $data) ? $data[$key] : $default;
    }
}
