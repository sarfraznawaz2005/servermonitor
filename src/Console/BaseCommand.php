<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/12/2019
 * Time: 2:47 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Console;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{

    /**
     * Outputs check results on console.
     *
     * @param array $results
     */
    protected function outputResults(array $results)
    {
        $data = [];

        foreach ($results as $type => $checks) {
            foreach ($checks as $check) {
                $name = $check['name'];
                $message = 'None';

                if ($check['result']) {
                    $result = '<fg=green>PASSED</fg=green>';
                } else {
                    $message = $check['message'];
                    $result = '<fg=red>FAILED</fg=red>';
                }

                if (!app()->runningInConsole()) {
                    echo '<pre>';
                    $message = nl2br($message);
                }

                $data[] = [$type, $name, $result, $message];
            }
        }

        $headers = ['Check Type', 'Check Name', 'Result', 'Error'];

        $this->table($headers, $data);
    }
}
