<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/15/2019
 * Time: 5:29 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Senders;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class Log extends BaseSender implements Sender
{
    /**
     * Sends notificatin/alert message.
     *
     * @param Check $check
     * @param array $config
     * @return mixed
     */
    public function send(Check $check, array $config)
    {
        $title = $config['notification_title'] ?? config('server-monitor.notifications.notification_title');
        $name = $this->getName($check, $config);
        $error = $check->message();

        $body = "$title : $name ($error)";

        \Log::critical($body);
    }
}
