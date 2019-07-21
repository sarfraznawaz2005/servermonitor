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
use Illuminate\Support\Facades\Storage;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class CloudStorage implements Check
{
    private $disks;

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        $this->disks = Collection::make(Arr::get($config, 'disks', []));

        $this->disks = $this->disks->reject(static function ($disk) use ($config) {
            $file = $config['file'];
            $content = $config['content'];

            try {
                Storage::disk($disk)->put($file, $content);

                $contents = Storage::disk($disk)->get($file);

                Storage::disk($disk)->delete($file);

                if ($contents !== $content) {
                    return false;
                }

                return true;
            } catch (\Exception $exception) {
                return false;
            }
        });

        return $this->disks->isEmpty();
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message(): string
    {
        return "Cloud Storage failed for:\n" . $this->disks->implode(PHP_EOL);
    }
}
