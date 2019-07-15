<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/15/2019
 * Time: 4:50 PM
 */

namespace Sarfraznawaz2005\ServerMonitor;

use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class Notifier
{
    /**
     * Sends notification/alert if a check fails.
     *
     * @param Check $check
     * @param array $config
     * @return mixed
     */
    public static function notify(Check $check, array $config)
    {
        $envs = config('server-monitor.notifications.notification_enabled_on');

        if (!in_array(config('app.env'), $envs, true)) {
            return false;
        }

        if (isset($config['disable_notification']) && $config['disable_notification']) {
            return false;
        }

        $channel = $config['notification_channel'] ??
            config('server-monitor.notifications.notification_channel');

        if (!$channel) {
            throw new \UnexpectedValueException('Invalid Notification Channel.');
        }

        $channel = ucfirst(strtolower($channel));

        $class = "Sarfraznawaz2005\\ServerMonitor\\Senders\\$channel";

        return (new $class())->send($check, $config);
    }
}
