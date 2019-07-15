<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/15/2019
 * Time: 5:23 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Senders;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

interface Sender
{
    /**
     * Sends notificatin/alert message.
     *
     * @param Check $check
     * @param array $config
     * @return mixed
     */
    public function send(Check $check, array $config);
}
