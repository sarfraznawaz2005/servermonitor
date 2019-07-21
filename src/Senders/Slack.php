<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 7/15/2019
 * Time: 5:29 PM
 */

namespace Sarfraznawaz2005\ServerMonitor\Senders;

use Maknz\Slack\Client;
use Sarfraznawaz2005\ServerMonitor\Checks\Check;

class Slack extends BaseSender implements Sender
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
        $url = config('server-monitor.notifications.notification_slack_hook_url');
        $title = $config['notification_title'] ?? config('server-monitor.notifications.notification_title');
        $channel = $config['notification_slack_channel'] ?? config('server-monitor.notifications.notification_slack_channel');
        $icon = $config['notification_slack_icon'] ?? config('server-monitor.notifications.notification_slack_icon');
        $color = $config['notification_slack_color'] ?? config('server-monitor.notifications.notification_slack_color');

        $name = $this->getName($check, $config);
        $error = $check->message();

        try {
            (new Client($url))
                ->to($channel)
                ->attach([
                    'text' => $title,
                    'color' => $color,
                    'icon' => $icon,
                    'fields' => [
                        [
                            'title' => $name,
                            'value' => $error,
                        ],
                    ]
                ])
                ->send();
        } catch (\Exception $e) {
            \Log::error('Server Monitor Error: ' . $e->getMessage());
        }
    }
}
