<?php

if (!function_exists('getCheckerName')) {
    /**
     * Returns checker class name
     *
     * @param $namespace
     * @param $config
     * @return string
     * @throws ReflectionException
     */
    function getCheckerName($namespace, $config)
    {
        return $config['name'] ?? normalizeCheckerName(getCheckerClassName($namespace));
    }
}

if (!function_exists('getCheckerClassName')) {
    /**
     * Returns class name from FQN
     *
     * @param $namespace
     * @return mixed
     * @throws ReflectionException
     */
    function getCheckerClassName($namespace)
    {
        if (is_object($namespace)) {
            return (new \ReflectionClass($namespace))->getShortName();
        }

        $path = explode('\\', $namespace);

        return array_pop($path);
    }
}

if (!function_exists('normalizeCheckerName')) {
    /**
     * Converts "PascalCase" to "Pascal Case"
     *
     * @param $name
     * @return string
     */
    function normalizeCheckerName($name): string
    {
        return ucwords(strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $name)));
    }
}
