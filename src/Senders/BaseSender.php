<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/21/2019
 * Time: 8:53 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Senders;

abstract class BaseSender
{
    protected function getName($namespace, $config): string
    {
        return $config['name'] ?? $this->normalizeName($this->getClassName($namespace));
    }

    /**
     * Returns class name from FQN
     *
     * @param $namespace
     * @return mixed
     */
    public function getClassName($namespace)
    {
        $path = explode('\\', $namespace);

        return array_pop($path);
    }

    /**
     * Converts "PascalCase" to "Pascal Case"
     *
     * @param $name
     * @return string
     */
    protected function normalizeName($name): string
    {
        return ucwords(strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $name)));
    }
}
