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

        $this->info(
            '<fg=green>Passed: ' . $results['counts']['passed_checks_count'] . "</fg=green>\t" .
            '<fg=red>Failed: ' . $results['counts']['failed_checks_count'] . "</fg=red>\t" .
            '<fg=yellow>Total: ' . $results['counts']['total_checks_count'] . '</fg=yellow>'
        );

        unset($results['counts']);

        foreach ($results as $type => $checks) {

            $type = ucwords(str_replace('.', ' ', $type));

            foreach ($checks as $check) {
                $error = '';

                if ($check['status']) {
                    $status = '<fg=green>PASSED</fg=green>';
                } else {
                    $error = $check['error'];
                    $status = '<fg=red>FAILED</fg=red>';
                }

                $data[] = [$type, $check['name'], $status, $check['time'], $error];
            }
        }

        $headers = ['Check Type', 'Check Name', 'Status', 'Time', 'Error'];

        $this->table($headers, $data);
    }

    /**
     * Outputs check result on console.
     *
     * @param array $result
     */
    protected function outputResult(array $result)
    {
        $error = '';

        if ($result['status']) {
            $status = '<fg=green>PASSED</fg=green>';
        } else {
            $error = $result['error'];
            $status = '<fg=red>FAILED</fg=red>';
        }

        $result['type'] = ucwords(str_replace('.', ' ', $result['type']));

        $data[] = [$result['type'], $result['name'], $status, $result['time'], $error];

        $headers = ['Check Type', 'Check Name', 'Status', 'Time', 'Error'];

        $this->table($headers, $data);
    }
}
