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

class Slack implements Sender
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
        $title = $config['notification_subject'] ?? config('server-monitor.notifications.notification_subject');
        $name = $check->name();
        $error = $check->message();

        $body = "$title : $name ($error)";

        $client = new Client(config('server-monitor.notifications.notification_slack_hook_url'));
        $client->setDefaultUsername(config('server-monitor.notifications.notification_slack_username'));
        $client->setDefaultIcon(config('server-monitor.notifications.notification_slack_icon'));

        $client
            ->to(config('server-monitor.notifications.notification_slack_channel'))
            ->attach([
                'text' => $body,
                'color' => config('server-monitor.notifications.notification_slack_color')
            ])
            ->send($title);
    }
}
