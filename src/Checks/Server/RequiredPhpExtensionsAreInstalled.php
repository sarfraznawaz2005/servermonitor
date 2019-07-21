<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/10/2019
 * Time: 2:39 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Checks\Server;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class RequiredPhpExtensionsAreInstalled implements Check
{
    const EXT = 'ext-';

    private $filesystem;
    private $extensions;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function check(array $config): bool
    {
        $this->extensions = Collection::make(Arr::get($config, 'extensions', []));

        if (Arr::get($config, 'include_composer_extensions', false)) {
            $this->extensions = $this->extensions->merge($this->getExtensionsRequiredInComposerFile());
            $this->extensions = $this->extensions->unique();
        }

        $this->extensions = $this->extensions->reject(function ($ext) {
            return extension_loaded($ext);
        });

        return $this->extensions->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "The following extensions are missing:\n" . $this->extensions->implode(PHP_EOL);
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getExtensionsRequiredInComposerFile(): array
    {
        $extensions = [];

        $installedPackages = json_decode($this->filesystem->get(base_path('vendor/composer/installed.json')), true);

        foreach ($installedPackages as $installedPackage) {
            $filtered = Arr::where(array_keys(Arr::get($installedPackage, 'require', [])), function ($value, $key) {
                return starts_with($value, self::EXT);
            });

            foreach ($filtered as $extension) {
                $extensions[] = str_replace_first(self::EXT, '', $extension);
            }
        }

        return array_unique($extensions);
    }
}
