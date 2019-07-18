<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;
use SensioLabs\Security\SecurityChecker as SensioLabsSecurityChecker;

class SecurityChecker implements Check
{
    private $problems;
    private $error;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Check Composer Packages Security';
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
            $checker = new SensioLabsSecurityChecker();

            $alerts = $checker->check(base_path('composer.lock'));

            if (count($alerts) === 0) {
                return true;
            }

            $this->problems = $alerts;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
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
        if ($this->error) {
            return $this->error;
        }

        return "The following packages have vulnerabilities referenced\n in the SensioLabs security advisories database:\n" . collect($this->problems)->keys()->implode(PHP_EOL);
    }
}
