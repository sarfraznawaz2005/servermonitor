<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Application;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Sarfraznawaz2005\ServerMonitor\Contract\Check;
use Composer\Semver\Semver;

class CorrectPhpVersionInstalled implements Check
{
    private $filesystem;
    private $requiredVersion = 0;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        try {
            $this->requiredVersion = $this->getRequiredPhpConstraint();
        } catch (FileNotFoundException $e) {
        }
    }

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Correct PHP version installed';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        return Semver::satisfies(
            PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION,
            $this->requiredVersion
        );
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        $required = $this->requiredVersion;
        $used = PHP_VERSION;

        return "Required PHP version is not installed.\nRequired: $required, used: $used";
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getRequiredPhpConstraint()
    {
        $composer = json_decode($this->filesystem->get(base_path('composer.json')), true);

        return Arr::get($composer, 'require.php');
    }
}
