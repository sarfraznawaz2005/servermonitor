<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:40 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Contract;


interface Check
{
    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool;

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string;
}
